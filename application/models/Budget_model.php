<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_model extends CI_Model{
	function get_budget_to_date($budget_id = "",$month_count=""){
		//Compute budget to date		
		$this->db->where(array("month<="=>$month_count,"budget_spread.budget_id"=>$budget_id));//To compute month 3
		$this->db->join("budget","budget.budget_id=budget_spread.budget_id");
		$budget_to_date_obj = $this->db->select_sum('amount')->get("budget_spread");
		$budget_to_date = $budget_to_date_obj->num_rows() > 0?$budget_to_date_obj->row()->amount:0;
		
		return $budget_to_date;
	}
	
	function get_staff_id_from_budget($budget_id=""){
		$staff_id = $this->db->select("staff_code")->get_where("budget",array("budget_id"=>$budget_id))->row()->staff_code;
		return $staff_id;
	}
	
	function compute_dea_expense_to_date($month="",$office_id=""){
				
		//Start of year bva balances
		$start_of_the_year = date('Y-m-01',strtotime('first day of january',strtotime($month)));
		
		$start_of_the_month = date('Y-m-01',strtotime($month));
		$end_of_the_month = date('Y-m-t',strtotime($month));
		
		$this->db->select(array('bva_update.dea_id','update_month'));
		$this->db->select_sum('balance');
		$this->db->group_by('bva_update.dea_id');
		$this->db->join('dea','dea.dea_id=bva_update.dea_id');
		if($office_id!=="") $this->db->where(array('dea.office_id'=>$office_id));
		$start_bva = $this->db->get_where('bva_update',array('update_month'=>$start_of_the_year));
		
		//Current BVA Balance
		$this->db->select(array('bva_update.dea_id','update_month'));
		$this->db->select_sum('balance');
		$this->db->group_by('bva_update.dea_id');
		$this->db->join('dea','dea.dea_id=bva_update.dea_id');
		if($office_id!=="") $this->db->where(array('dea.office_id'=>$office_id));
		$current_bva = $this->db->get_where('bva_update',array('update_month'=>date('Y-m-01',strtotime($month))));
		
		$assigned = array();
		
		if($start_bva->num_rows() > 0 && $current_bva->num_rows() > 0){
			//Construct a merged array of keys DEA_ids and values as balances - Current BVA 
			$current_bva_array = $current_bva->result_array(); 
			$current_bva_deas = array_column($current_bva_array, 'dea_id');
			$current_bva_deas_amounts = array_column($current_bva_array, 'balance');
			$current_bva_deas_amounts_merger = array_combine($current_bva_deas, $current_bva_deas_amounts);
			
			//Construct a merged array of keys DEA_ids and values as balances - Current BVA 
			$start_bva_array = $start_bva->result_array(); 
			$start_bva_deas = array_column($start_bva_array, 'dea_id');
			$start_bva_deas_amounts = array_column($start_bva_array, 'balance');
			$start_bva_deas_amounts_merger = array_combine($start_bva_deas, $start_bva_deas_amounts);
			
			//Check dea differences betweeen start and current bva updates
			$dea_in_current_but_miss_in_start_bva = array_diff_key($current_bva_deas_amounts_merger, $start_bva_deas_amounts_merger);
			$dea_in_start_but_miss_in_current_bva = array_diff_key($start_bva_deas_amounts_merger,$current_bva_deas_amounts_merger);
			
			//Assigning missing DEA IDs
			if(count($dea_in_current_but_miss_in_start_bva) > 0){
				foreach($dea_in_current_but_miss_in_start_bva as $dea_id=>$balance){
					$this->db->select('min(update_month) as update_month');
					$first_occurence_date = $this->db->get_where('bva_update',array('dea_id'=>$dea_id))
					->row()->update_month;
					
					
					$this->db->select('balance');
					$start_bva_deas_amounts_merger[$dea_id] = $this->db->get_where('bva_update',
					array('update_month'=>$first_occurence_date))->row()->balance;
				}
			}
			
			if(count($dea_in_start_but_miss_in_current_bva) > 0){
				foreach($dea_in_start_but_miss_in_current_bva as $dea_id=>$balance){
					$current_bva_deas_amounts_merger[$dea_id] = 0;
				}
			}
			
			//Calculate months expense update
			$string_where =  'month BETWEEN "'.$start_of_the_month.'" AND "'.$end_of_the_month.'"'; 
			$this->db->where($string_where);
			$this->db->select_sum('amount');
			$this->db->select(array('dea_id'));
			$this->db->group_by('dea_id');
			$expense_updates = $this->db->get('expense');
			
			$expense_updates_array = array();
			
			if($expense_updates->num_rows() > 0){
				$dea_ids_with_expense_updates = array_column($expense_updates->result_array(), 'dea_id');//array_merge(array_column($expense_updates->result_array(), 'dea_id'),
				//array_column($expense_updates->result_array(), 'amount'));
				$expenses_per_dea_id = array_column($expense_updates->result_array(), 'amount');
				
				$expense_updates_array = array_combine($dea_ids_with_expense_updates, $expenses_per_dea_id);
			}
			
			//Calculate active commitment update
			$this->db->select_sum('amount');
			$this->db->select(array('dea_id'));
			$this->db->group_by('dea_id');
			$commitment_updates = $this->db->get('commitment');
			
			$commitment_updates_array = array();
			
			if($commitment_updates->num_rows() > 0){
				$dea_ids_with_commitment_updates = array_column($commitment_updates->result_array(), 'dea_id');//array_merge(array_column($expense_updates->result_array(), 'dea_id'),
				//array_column($expense_updates->result_array(), 'amount'));
				$commitment_per_dea_id = array_column($commitment_updates->result_array(), 'amount');
				
				$commitment_updates_array = array_combine($dea_ids_with_commitment_updates, $commitment_per_dea_id);
			}
			
			//Compute expense to date
			$expense_to_date = array();
			foreach(array_keys($start_bva_deas_amounts_merger) as $dea_id){
				$expense_update = 0;
				if(isset($expense_updates_array[$dea_id])){
					$expense_update = $expense_updates_array[$dea_id];
				}
				
				$commitment_update = 0;
				
				if(isset($commitment_updates_array[$dea_id])){
					$commitment_update = $commitment_updates_array[$dea_id];
				}
				
				$expense_to_date[$dea_id] = ($start_bva_deas_amounts_merger[$dea_id]- $current_bva_deas_amounts_merger[$dea_id])+ $expense_update + $commitment_update;
			}
		
		}
		
		return $expense_to_date;
	}

	
	function get_budget($budget_id=""){
		$budget = $this->db->get_where("budget",array("budget_id"=>$budget_id))->row();		
		return $budget;
	}
	
	function get_budget_spread($budget_id=""){
		$budget_spread = $this->db->get_where("budget_spread",array("budget_id"=>$budget_id))->result_object();
		
		return $budget_spread;
	}
	
	function get_office_dea($office_code=""){
		$this->db->where(array("dea.office_id"=>$office_code));
		$this->db->join("sof","sof.sof_id = dea.sof_id");
		$this->db->join("office","office.office_id=dea.office_id");
		$this->db->select(array("dea.dea_id","dea.dea_code","sof.sof_code","sof.name as sof_name","office.name as office_name","dea.office_id","initial_amount"));
		$bva = $this->db->get("dea")->result_object();
		
		return $bva;
	}
	
	function get_budget_allocation($budget_id=""){
		$this->db->where(array("allocation.budget_id"=>$budget_id));
		$this->db->join("budget","budget.budget_id=allocation.budget_id");
		$this->db->select(array("dea_code","amount"));
		$allocations_obj = $this->db->get("allocation");
		$allocations = $allocations_obj->num_rows()>0?$allocations_obj->result_object():array();
		
		return $allocations;
	}
	
	function add_allocation_to_account_staff_record($accounts,$year){
		
		$this->db->select(array('budget_id','dea_id','amount'));
		$allocations_for_the_year = $this->db->get_where('allocation',array('alloc_year'=>$year));
		
		$accounts_with_dea = array();
		
		if($allocations_for_the_year->num_rows() > 0){
			
			foreach($accounts as $account){
				$add_allocations = array();
				foreach($allocations_for_the_year->result_object() as $dea){
					if($account->budget_id == $dea->budget_id){
						$add_allocations[] = $dea;
					}
				}
				
				$account->allocation = (object)$add_allocations;
				
				$accounts_with_dea[] = $account;
			}
			
		}else{
			$accounts_with_dea = $accounts;
		}
		
		return $allocations_for_the_year->result_object();
	}

	function accounts_with_dea($office_id,$start_date,$related_table,$budget_type){
		
		$first_day_of_the_year = date('Y-m-01',strtotime('first day of january',strtotime($start_date)));
		$last_day_of_the_year = date('Y-m-t',strtotime('last day of december',strtotime($start_date)));
		
		$budget_section_id = $this->db->get_where('budget_section',array('short_name'=>$budget_type))->row()
		->budget_section_id;
		
		$where_string = "start_date >= '".$first_day_of_the_year."' AND end_date <= '".$last_day_of_the_year."' AND 
		budget_section_id = ".$budget_section_id;
		$this->db->where($where_string);
		$this->db->select_sum('amount');
		$this->db->select(array($related_table.'_id','name',$related_table.'_code','office_code as office_id','budget.budget_id','start_date'));
		$this->db->join("budget","budget.related_table_primary_key_value=".$related_table.".".$related_table."_id");
		$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
		$this->db->group_by(array('budget_spread.budget_id'));
		$accounts = $this->db->get_where($related_table,array('office_code'=>$office_id))
		->result_object();
		
		
		$year = date('Y',strtotime($start_date));
		
		
		$this->db->select(array('budget_id','dea_id','amount as alloc_amount'));
		$allocations_for_the_year = $this->db->get_where('allocation',array('alloc_year'=>$year));
		
		$accounts_with_dea = array();
		
		if($allocations_for_the_year->num_rows() > 0){
			
			foreach($accounts as $account){
				$add_allocations = array();
				foreach($allocations_for_the_year->result_object() as $dea){
					if($account->budget_id == $dea->budget_id){
						$add_allocations[] = $dea;
					}
				}
				
				$account->allocation = (object)$add_allocations;
				
				$accounts_with_dea[] = $account;
			}
			
		}else{
			$accounts_with_dea = $accounts;
		}
		
		return $accounts_with_dea;
	}
	
}
