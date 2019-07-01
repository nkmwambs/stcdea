<?php

class Stcdea_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_table_results($table) {

		$results = $this -> db -> get($table) -> result_object();

		return $results;
	}

	function user_restriction_objects($group_by = "user_id", $user_id = "") {

		//Group By Options: user_id, restriction_object_name, restriction_value_id

		$this -> db -> select(array('restriction_object_name', 'user.name', 'user_restriction.user_id', 'restriction_value_id'));

		if ($user_id !== "") {
			$this -> db -> where(array('user_restriction.user_id' => $user_id));
		}

		$this -> db -> join('user_restriction_value', 'user_restriction_value.user_restriction_id=user_restriction.user_restriction_id');
		$this -> db -> join('user', 'user.user_id=user_restriction.user_id');
		$this -> db -> join('restriction_object', 'restriction_object.restriction_object_id=user_restriction.restriction_object_id');
		$results = $this -> db -> get('user_restriction') -> result_array();

		return group_array_by_key($results, $group_by);
	}

	function get_restricted_objects($user_id, $object) {

		//Object options: office,sof

		$user_restriction = $this -> user_restriction_objects('restriction_object_name', $user_id);

		$objects_array = array();

		if (array_key_exists($object, $user_restriction)) {
			$objects_array = array_column($user_restriction[$object], 'restriction_value_id');
		}

		return $objects_array;
	}

	function get_lasted_year_forecast($year) {

		$this -> db -> select_max('forecast_period');
		$this -> db -> where(array('YEAR(start_date)' => $year));
		return $this -> db -> get_where('budget') -> row() -> forecast_period;
	}

	function ytd_actuals_for_the_year($month_start_date, $office_id) {

		/**YTD Actuals for the year grouped by dea**/

		$where_string = "update_month BETWEEN '" . date('Y-m-01', strtotime('first day of january', strtotime($month_start_date))) . "' AND '" . $month_start_date . "'";

		if ($office_id != "")
			$where_string = " shared_dea.office_id = " . $office_id;

		$this -> db -> select(array('bva_update.dea_id'));
		$this -> db -> select('SUM(bva_update.month_actual) as ytd_actual');
		$this -> db -> group_by(array('bva_update.dea_id','shared_dea.office_id'));
		$this -> db -> where($where_string);
		$this -> db -> join('dea', 'dea.dea_id = bva_update.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id = dea.dea_id');
		$ytd_actual = $this -> db -> get('bva_update') -> result_array();

		$ytd_actual_amount = array_column($ytd_actual, 'ytd_actual');
		$ytd_actual_dea = array_column($ytd_actual, 'dea_id');
		$dea_keyed_ytd_actual = array_combine($ytd_actual_dea, $ytd_actual_amount);

		return $dea_keyed_ytd_actual;
	}

	function opening_loa_actuals($month_start_date, $office_id) {

		/**Get Past Year LOA Actuals from dea initial amount table**/

		$this -> db -> select(array('dea.dea_id', 'initial_amount'));
		if ($office_id != "")
			$this -> db -> where(array('shared_dea.office_id' => $office_id));

		$this -> db -> join('shared_dea', 'shared_dea.dea_id=dea.dea_id');
		$past_loa_actual = $this -> db -> get('dea') -> result_array();

		$past_loa_actual_amount = array_column($past_loa_actual, 'initial_amount');
		$past_loa_actual_dea = array_column($past_loa_actual, 'dea_id');
		$dea_keyed_past_loa_actual = array_combine($past_loa_actual_dea, $past_loa_actual_amount);

		return $dea_keyed_past_loa_actual;
	}


	function year_loa_actuals($month_start_date, $office_id) {

		/**LOA Actuals for the year grouped by dea**/

		$where_string = "update_month <='" . $month_start_date . "'";
		if ($office_id != "")
			$where_string .= " AND shared_dea.office_id = " . $office_id;

		$this -> db -> select('SUM(bva_update.month_actual) as loa_actual');
		$this -> db -> select(array('bva_update.dea_id'));
		$this -> db -> group_by(array('bva_update.dea_id','shared_dea.office_id'));
		$this -> db -> where($where_string);

		$this -> db -> join('dea', 'dea.dea_id = bva_update.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id = shared_dea.dea_id');
		$loa_actual = $this -> db -> get('bva_update') -> result_array();

		$loa_actual_amount = array_column($loa_actual, 'loa_actual');
		$loa_actual_dea = array_column($loa_actual, 'dea_id');
		$dea_keyed_loa_actual = array_combine($loa_actual_dea, $loa_actual_amount);

		return $dea_keyed_loa_actual;
	}

	function month_expenses_per_dea($month_start_date, $office_id) {

		/**Calculate Expense for the month**/
		
		$this -> db -> select('expense.dea_id');
		$this -> db -> select_sum('amount');

		$this -> db -> where(array('month>=' => $month_start_date, 'month<=' => date('Y-m-t', strtotime($month_start_date))));
		
		if ($office_id != "")
		{
			$this -> db -> where(array('shared_dea.office_id' => $office_id));	
		}

		$this -> db -> group_by(array('expense.dea_id','shared_dea.office_id'));

		$this -> db -> join('dea', 'dea.dea_id = expense.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id = dea.dea_id');
		$month_expense = $this -> db -> get('expense') -> result_array();

		$expense_amount = array_column($month_expense, 'amount');
		$expense_dea = array_column($month_expense, 'dea_id');
		$dea_keyed_expense = array_combine($expense_dea, $expense_amount);

		return $dea_keyed_expense;
	}

	function month_commitment_per_dea($month_start_date, $office_id) {

		/**Calculate Commitment for the month**/
		$this -> db -> select('commitment_detail.dea_id');
		$this -> db -> select_sum('commitment_detail.amount');
		if ($office_id != "")
			$this -> db -> where(array('shared_dea.office_id' => $office_id));

		$this -> db -> group_by(array('commitment_detail.dea_id','shared_dea.office_id'));
		$this -> db -> join('commitment_detail', 'commitment_detail.commitment_id = commitment.commitment_id');
		$this -> db -> join('dea', 'dea.dea_id = commitment_detail.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id = dea.dea_id');
		$month_commitment = $this -> db -> get('commitment') -> result_array();

		$commitment_amount = array_column($month_commitment, 'amount');
		$commitment_dea = array_column($month_commitment, 'dea_id');
		$dea_keyed_commitment = array_combine($commitment_dea, $commitment_amount);

		return $dea_keyed_commitment;
	}

	function budget_allocated_amount_per_dea($month_start_date, $office_id) {

		/**Budget Allocated ammount**/
		$latest_forecast = $this -> get_lasted_year_forecast(date('Y', strtotime($month_start_date)));
		$where_string = "alloc_year = YEAR('" . $month_start_date . "') AND forecast_period = " . $latest_forecast;

		$this -> db -> select_sum('amount');
		$this -> db -> select('allocation.dea_id');

		if ($office_id != "")
			$this -> db -> where(array('shared_dea.office_id' => $office_id));

		$this -> db -> where($where_string);
		$this -> db -> join('dea', 'dea.dea_id = allocation.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id = dea.dea_id');
		$this -> db -> join('budget', 'budget.budget_id=allocation.budget_id');
		$this -> db -> group_by(array('allocation.dea_id','shared_dea.office_id'));
		$allocation = $this -> db -> get('allocation') -> result_array();

		$allocation_amount = array_column($allocation, 'amount');
		$allocation_dea = array_column($allocation, 'dea_id');
		$dea_keyed_allocation = array_combine($allocation_dea, $allocation_amount);

		return $dea_keyed_allocation;
	}

	function month_bva_parameters($month_start_date, $office_id) {
		/**Current month BVA Update**/

		$this -> db -> select(array('dea.dea_id', 'bva_update.update_month', 'bva_update.month_actual', 'month_forecast', 'ytd_forecast', 'year_forecast', 'loa_forecast', 'year_remaining_balance'));

		$this -> db -> where(array('update_month' => $month_start_date));

		if ($office_id != "") {
			$this -> db -> where(array('shared_dea.office_id' => $office_id));
		}

		$this -> db -> join('dea', 'dea.dea_id=bva_update.dea_id');
		$this -> db -> join('shared_dea', 'shared_dea.dea_id=dea.dea_id');
		$this -> db -> join('sof', 'sof.sof_id=dea.sof_id');
		$this -> db -> join('office', 'office.office_id=shared_dea.office_id');

		$bva_updates = $this -> db -> get('bva_update') -> result_array();

		//Construct month actual, month forecast, ytd forecast, year forecast, loa forecast and year_remaining_balance

		$combined_array = array();

		if (count($bva_updates) > 0) {
			foreach (array_keys($bva_updates[0]) as $column) {

				if ($column == 'dea_id' || $column == 'update_month')
					continue;

				$amount = array_column($bva_updates, $column);
				$dea = array_column($bva_updates, 'dea_id');
				$dea_keyed = array_combine($dea, $amount);

				$combined_array[$column] = $dea_keyed;
			}
		}

		return $combined_array;
	}

	function loa_dea_balance($month_start_date, $office_id) {
		$combined_array = array();

		$dea_keyed_past_loa_actual = $this -> opening_loa_actuals($month_start_date, $office_id);
		$month_bva_parameters = $this -> month_bva_parameters($month_start_date, $office_id);

		//Compute LOA DEA Balance array
		$dea_keyed_loa_dea_balance = array();

		foreach (array_keys($dea_keyed_past_loa_actual) as $dea_id) {
			$loa_forecast = isset($month_bva_parameters['loa_forecast'][$dea_id]) ? $month_bva_parameters['loa_forecast'][$dea_id] : 0;
			$initial_loa_actuals = isset($dea_keyed_past_loa_actual[$dea_id]) ? $dea_keyed_past_loa_actual[$dea_id] : 0;
			$loa_actuals = isset($dea_keyed_loa_actual[$dea_id]) ? $dea_keyed_loa_actual[$dea_id] : 0;
			$sum_of_loa_actuals = $initial_loa_actuals + $loa_actuals;
			$dea_keyed_loa_dea_balance[$dea_id] = $loa_forecast - $sum_of_loa_actuals;
		}


		return $dea_keyed_loa_dea_balance;
	}

	function full_dea_balance($month_start_date,$office_id) {
		
		$dea_keyed_ytd_actual = $this -> ytd_actuals_for_the_year($month_start_date, $office_id);
		$month_bva_parameters = $this->month_bva_parameters($month_start_date, $office_id);
		$dea_keyed_expense = $this -> month_expenses_per_dea($month_start_date, $office_id);
		$dea_keyed_commitment = $this -> month_commitment_per_dea($month_start_date, $office_id);
		
		//Compute Full Year DEA Balance array
		
		$dea_keyed_year_dea_balance = array();
		foreach (array_keys($dea_keyed_ytd_actual) as $dea_id) {
			$year_forecast = isset($month_bva_parameters['year_forecast'][$dea_id]) ? $month_bva_parameters['year_forecast'][$dea_id] : 0;
			
			$year_actuals = isset($dea_keyed_ytd_actual[$dea_id]) ? $dea_keyed_ytd_actual[$dea_id] : 0;
			
			$month_expense = isset($dea_keyed_expense[$dea_id]) ? $dea_keyed_expense[$dea_id] : 0;
		
			$month_commitment = isset($dea_keyed_commitment[$dea_id]) ? $dea_keyed_commitment[$dea_id] : 0;
		
			$dea_keyed_year_dea_balance[$dea_id] = $year_forecast - ($year_actuals + $month_expense + $month_commitment);

		}		
		
		return $dea_keyed_year_dea_balance;
	}
	
	function ytd_forecast_allocation_gap($month_start_date, $office_id){
		
		$dea_keyed_year_dea_balance = $this->full_dea_balance($month_start_date, $office_id);
		$dea_keyed_allocation = $this -> budget_allocated_amount_per_dea($month_start_date, $office_id);
				
		//Compute allocation gap based on YTD forecast balance

		$dea_keyed_allocation_dea_balance = array();
		foreach (array_keys($dea_keyed_year_dea_balance) as $dea_id) {
			$year_forecast_balance = isset($dea_keyed_year_dea_balance[$dea_id]) ? $dea_keyed_year_dea_balance[$dea_id] : 0;
			$dea_keyed_allocation = isset($dea_keyed_allocation[$dea_id]) ? $dea_keyed_allocation[$dea_id] : 0;

			$dea_keyed_allocation_dea_balance[$dea_id] = $year_forecast_balance - $dea_keyed_allocation;

		}

		return $dea_keyed_allocation_dea_balance;
	}
	
	function get_month_bva_update($month_start_date, $office_id = "", $budget_section_id = "") {

		/**YTD Actuals for the year grouped by dea**/
		$dea_keyed_ytd_actual = $this -> ytd_actuals_for_the_year($month_start_date, $office_id);

		/**Get Past Year LOA Actuals from dea initial amount table**/
		$dea_keyed_past_loa_actual = $this -> opening_loa_actuals($month_start_date, $office_id);

		/**LOA Actuals for the year grouped by dea**/
		$dea_keyed_loa_actual = $this -> year_loa_actuals($month_start_date, $office_id);

		/**Calculate Expense for the month**/
		$dea_keyed_expense = $this -> month_expenses_per_dea($month_start_date, $office_id);

		/**Calculate Commitment for the month**/
		$dea_keyed_commitment = $this -> month_commitment_per_dea($month_start_date, $office_id);

		/**Budget Allocated ammount**/
		$dea_keyed_allocation = $this -> budget_allocated_amount_per_dea($month_start_date, $office_id);
		
		/**Compute LOA DEA Balance array**/
		$dea_keyed_loa_dea_balance = $this -> loa_dea_balance($month_start_date, $office_id);
		
		/**Compute Full Year DEA Balance array**/
		$dea_keyed_year_dea_balance = $this->full_dea_balance($month_start_date, $office_id);
		
		//Compute allocation gap based on YTD forecast balance
		$dea_keyed_allocation_dea_balance = $this->ytd_forecast_allocation_gap($month_start_date, $office_id);
		
		//Form a combined array for YTD Actuals, Past LOA Actuals, LOA Actuals, Expense and Commitment
		$combined_array = array();
		$combined_array['ytd_actuals'] = $dea_keyed_ytd_actual;
		$combined_array['initial_loa_actuals'] = $dea_keyed_past_loa_actual;
		$combined_array['loa_actuals'] = $dea_keyed_loa_actual;
		$combined_array['expenses'] = $dea_keyed_expense;
		$combined_array['commitments'] = $dea_keyed_commitment;
		$combined_array['ytd_allocations'] = $dea_keyed_allocation;
		$combined_array['loa_dea_balance'] = $dea_keyed_loa_dea_balance;
		$combined_array['year_forecast_balance'] = $dea_keyed_year_dea_balance;
		$combined_array['year_allocation_balance'] = $dea_keyed_allocation_dea_balance;
		
		/**Current month BVA Update - Combined several keys e.g. year_remaining_balance**/
		$combined_array = array_merge($combined_array, $this -> month_bva_parameters($month_start_date, $office_id));
		
		
		return $combined_array;

	}

	function get_month_bva_update_grouped_by_period($month_start_date,$office_id = ""){
		
		$month_bva_update = $this->get_month_bva_update($month_start_date,$office_id);
		
		/**Check if there is a bva_update**/
		$bva_records_count = $this->db->get_where('bva_update',array('update_month'=>$month_start_date))->num_rows();
		
		//Check user restriction by sof and office
		$office = $this->get_restricted_objects($this->session->login_user_id,'office');
		$sof = $this->get_restricted_objects($this->session->login_user_id,'sof');
		
		/**Get all DEAs**/
		$string_condition = "sof.start_date <= '".date('Y-m-01',strtotime('first day of january',strtotime($month_start_date)))."' AND end_date >= '".date('Y-m-t',strtotime('last day of december',strtotime($month_start_date)))."'";
		
		$this->db->select(array('sof.name as sof','sof.sof_code','dea.dea_code','dea.dea_id','dea.description'));
		
		if(count($office)> 0 ) $this->db->where_in('shared_dea.office_id',$office);
		if(count($sof)> 0 ) $this->db->where_in('sof.sof_id',$sof);
		
		$this->db->where($string_condition);
		
		$this->db->group_by(array('dea_id'));
		$this->db->join('sof','sof.sof_id=dea.sof_id');
		$this->db->join('shared_dea','shared_dea.dea_id=dea.dea_id');
		$this->db->join('office','office.office_id=shared_dea.office_id');
		$deas_with_sof_and_office_information = $this->db->get('dea')->result_array();
		
		//Append month bva update to sof information
		
		$deas_with_sof_and_office_information_and_bva_month_updates = array();
		
		$loop = 0;
		
		$offices_grouped_per_dea = $this->offices_grouped_per_dea($month_start_date);
		
		foreach($deas_with_sof_and_office_information as $row){
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['dea_information'] = $row;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['dea_information']['office'] = implode(', ', $offices_grouped_per_dea[$row['dea_id']]);
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['dea_information']['update_month'] = $month_start_date;
			
			$month_expenses = isset($month_bva_update['expenses'][$row['dea_id']])?$month_bva_update['expenses'][$row['dea_id']]:0;
			$month_commitments = isset($month_bva_update['commitments'][$row['dea_id']])?$month_bva_update['commitments'][$row['dea_id']]:0;
			
			//Sum of month expenses and commitments
			$expense_and_commitment_sum = $month_expenses + $month_commitments;
			
			//Month actuals sum of the year from the bva updates
			$month_actual = (isset($month_bva_update['month_actual'][$row['dea_id']]) && isset($month_bva_update['month_actual']))?$month_bva_update['month_actual'][$row['dea_id']]:0;
			
			$month_actuals = $expense_and_commitment_sum + $month_actual;
			$month_forecast = isset($month_bva_update['month_forecast'][$row['dea_id']])?$month_bva_update['month_forecast'][$row['dea_id']]:0;
			$month_variance = $month_forecast - $month_actuals;
			$month_per_variance = ($month_forecast != 0)?($month_variance/$month_forecast):0;
			
			//ytd actuals sum of the year from the bva updates
			$ytd_actual = (isset($month_bva_update['ytd_actuals'][$row['dea_id']]) && $bva_records_count > 0)?$month_bva_update['ytd_actuals'][$row['dea_id']]:0;
				
			$ytd_forecast = isset($month_bva_update['ytd_forecast'][$row['dea_id']])?$month_bva_update['ytd_forecast'][$row['dea_id']]:0;
			$ytd_actuals = $expense_and_commitment_sum + $ytd_actual;
			$ytd_variance = $ytd_forecast - $ytd_actuals;
			$ytd_per_variance = ($ytd_forecast != 0)?($ytd_variance/$ytd_forecast):0;
			$year_remaining_balance = isset($month_bva_update['year_remaining_balance'][$row['dea_id']])?$month_bva_update['year_remaining_balance'][$row['dea_id']]:0;

			$year_forecast = isset($month_bva_update['year_forecast'][$row['dea_id']])?$month_bva_update['year_forecast'][$row['dea_id']]:0;
			$year_burn_rate = $year_forecast != 0?$ytd_actuals/$year_forecast:0;
			
			$loa_forecast = isset($month_bva_update['loa_forecast'][$row['dea_id']])?$month_bva_update['loa_forecast'][$row['dea_id']]:0;	
			$loa_actuals = $expense_and_commitment_sum + isset($month_bva_update['loa_actuals'][$row['dea_id']])  && $bva_records_count > 0?$month_bva_update['loa_actuals'][$row['dea_id']]:0;	
			$loa_burn_rate = $loa_forecast != 0?$loa_actuals/$loa_forecast:0;
									
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['month']['forecast'] = $month_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['month']['actual'] = $month_actuals;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['month']['variance'] = $month_variance;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['month']['per_variance'] = $month_per_variance;
			
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['ytd']['forecast'] = $ytd_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['ytd']['actual'] = $ytd_actuals;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['ytd']['variance'] = $ytd_variance;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['ytd']['per_variance'] = $ytd_per_variance;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['ytd']['year_remaining_balance'] = $year_remaining_balance;
			
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['full_year']['forecast'] = $year_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['full_year']['burn_rate'] = $year_burn_rate;
			
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['forecast'] = $loa_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['actual'] = $loa_actuals;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['burn_rate'] = $loa_burn_rate;
			
			$loop++;
		}
		
		return $deas_with_sof_and_office_information_and_bva_month_updates;
		
	}

	function offices_grouped_per_dea($month_start_date,$group_by_name = true){
		
		$string_condition = "sof.start_date <= '".date('Y-m-01',strtotime('first day of january',strtotime($month_start_date)))."' AND end_date >= '".date('Y-m-t',strtotime('last day of december',strtotime($month_start_date)))."'";
		
		$this->db->select(array('shared_dea.office_id','dea.dea_id','office.name'));
		$this->db->join('shared_dea','shared_dea.dea_id=dea.dea_id');
		$this->db->join('office','office.office_id=shared_dea.office_id');
		$this->db->join('sof','sof.sof_id=dea.sof_id');
		$this->db->where($string_condition);
		$offices_by_dea = $this->db->get('dea')->result_array();
		
		$grouped_by_dea = array();
		
		foreach ($offices_by_dea as $office) {
			if($group_by_name){
				$grouped_by_dea[$office['dea_id']][] = $office['name'];
			}else{
				$grouped_by_dea[$office['dea_id']][] = $office['office_id'];
			}
			
		}
		
		return $grouped_by_dea;
	}

}
