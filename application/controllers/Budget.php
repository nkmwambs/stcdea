<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *	@author 	: Nicodemus Karisa
 *	date		: 6th June, 2018
 *	STC BVA Allocation system
 *	https://www.techsysinc.com
 *	NKarisa@ke.ci.org
 */

class Budget extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		$this->load->model('budget_model');

		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', "budget");

       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

    }

    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
    }

	/** AJAX LOADED CONTENT START**/
	
	function group_years_budget_by_office($start_date,$end_date,$section_id,$office_id=""){
		
		
		//Get all offices
		if($office_id!=="") $this->db->where(array('office_code'=>$office_id));
		$offices = $this->db->select(array('office_id'))->get_where('office',array('status'=>1))->result_array();

		$active_office_id = array_column($offices, 'office_id');		
		
		//Get Lasted forecast period
		$lasted_forecast_period = $this->get_lasted_year_forecast(date('Y',strtotime($start_date)));
		
		//Get budget items
		
		$this->db->where(array('forecast_period'=>$lasted_forecast_period,'start_date>='=>$start_date,"end_date<="=>$end_date,"budget_section_id"=>$section_id));
		if($office_id!=="") $this->db->where(array('office_code'=>$office_id));
		$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
		if($section_id == 1){
			$this->db->select(array('global_key','forecast_period','budget.budget_id','budget.budget_section_id','budget.office_code',
			'related_table_primary_key_value','staff_code as item_code','staff.name as item_name','description','start_date','end_date','status',
			'month','budget_spread.amount'));
			$this->db->join('staff','staff.staff_id=budget.related_table_primary_key_value');
		}else{
			$this->db->select(array('global_key','forecast_period','budget.budget_id','budget.budget_section_id','budget.office_code',
			'budget.related_table_primary_key_value','budget_account.budget_account_code as item_code','budget_account.name as item_name',
			'budget.description','budget.start_date','budget.end_date','budget.status',
			'budget_spread.month','budget_spread.amount'));
			$this->db->join('budget_account','budget_account.budget_account_id=budget.related_table_primary_key_value');
		}
		$budget = $this->db->get("budget")->result_object();
		
		$grouped = array();
		
		$cnt = 1;
		foreach($budget as $row){
			$grouped[$row->office_code][$row->budget_id]['header'] = array('global_key'=>$row->global_key,
			'forecast_period'=>$row->forecast_period,'budget_id'=>$row->budget_id,
			"budget_section_id"=>$row->budget_section_id,"office_code"=>$row->office_code,
			"related_table_primary_key_value"=>$row->related_table_primary_key_value,
			'item_code'=>$row->item_code,'item_name'=>$row->item_name,
			"description"=>$row->description,"start_date"=>$row->start_date,"end_date"=>$row->end_date,
			"status"=>$row->status);
			
			$grouped[$row->office_code][$row->budget_id]['spread'][$row->month] = $row->amount;
			
			$cnt++;
			
		}
		
		foreach($active_office_id as $office_id){
			if(!array_key_exists($office_id, $grouped)){
				$grouped[$office_id] = array();
			}
		}
		
		
		return $grouped;
	}
	
	
	function costing($param1="",$section_id="",$office_id=""){
		
		if($param1=="") $param1 = strtotime(date("Y-m-01"));
				
		$month_count = date("n",$param1);
		
		$start_date = date('Y-m-01', strtotime('first day of january this year',$param1));
		
		$end_date = date('Y-m-t', strtotime('last day of december this year',$param1));
		
		$grouped = $this->group_years_budget_by_office($start_date,$end_date,$section_id,$office_id);
		
		$output['month_count'] = $month_count;
		$output['grouped'] = $grouped;
		$output['date'] = date('Y-m-d',$param1);
		$output['period_start_date'] = $start_date;
		$output['period_end_date'] = $end_date;
		
		return $output;
	}	
	
	function get_budgeted_related_table_ids_for_the_year($grouped_by_offices_budget){
		
		$budgeted_related_table_ids_list = array();
		
		foreach($grouped_by_offices_budget as $office_code=>$office_budget){
			foreach($office_budget as $budget_line){
				$budgeted_related_table_ids_list[] = $budget_line->related_table_primary_key_value;
			}
		}
		
		return $budgeted_related_table_ids_list;
	}
	
	function get_lasted_year_forecast($year){
		
		$this->db->select_max('forecast_period');
		$this->db->where(array('YEAR(start_date)'=>$year));
		return $this->db->get_where('budget')->row()->forecast_period;
	}

	

	
	function view_budget($param1="",$param2=""){
		if ($this->session->userdata('user_login') != 1 ||
			(!in_array(__FUNCTION__, $this->session->privileges) && $this->session->is_super_user != 1)
		)
            redirect(base_url() . 'login', 'refresh');
		
		$selected_date = strtotime($param2);
		
		//Check if logged user has field office budget access restriction
		// $check_restriction = $this->db->get_where('field_restriction',
		// array('restricted_to_object'=>'view_budget','role_id'=>$this->session->role_id))->num_rows();
		
		
		
		$office_id =  "";
		
		if(isset($_POST['office_id'])) {
			$office_id = $_POST['office_id'];
		}
		
		// if($check_restriction > 0){
			// $office_id = $this->session->office_id;	
			// $page_data['load_budget'] = true;
		// }
		
		if($param2=="") $selected_date = strtotime(date('Y-m-d'));	
				
		$budget_section_id = $this->db->get_where('budget_section',array('name'=>get_phrase($param1)))
		->row()->budget_section_id;
		
		extract($this->costing($selected_date,$budget_section_id,$office_id));
		
		$page_title = get_phrase($param1);

		$budget_section_id =  $this->db->get_where('budget_section',array('name'=>$page_title))
		->row()->budget_section_id;
		
		$budget_section_fields = $this->db->get_where('budget_section_field',
		array('budget_section_id'=>$budget_section_id))->result_object();
		
		//$page_data['test'] = $this->get_lasted_year_forecast($office_id,$budget_section_id,'2019');
		
		$page_data['office_id'] = "";
		
		if(isset($_POST['office_id'])){
			$page_data['office_id'] = $_POST['office_id'];
			$page_data['load_budget'] = true;
		}
		
		//Get user restriction by office
		$office_ids = $this->stcdea_model->get_restricted_objects($this->session->login_user_id,'office');
		
		$this->db->where('office.status',1);
		
		if(count($office_ids) > 0) $this->db->where_in('office_id',$office_ids);
		
		$offices =  $this->db->select(array('office_id','office_code','name'))->get('office')->result_object();
		
		$page_data['offices'] = $offices;
		$page_data['selected_date'] = $date;
		$page_data['budget_type'] = $param1;
		$page_data['budget_section_fields'] = $budget_section_fields;		
		$page_data['grouped'] = $grouped;
		$page_data['month'] = $month_count; 
		$page_data['first_month_day'] = $date;
		$page_data['period_start_date'] = $period_start_date;
		$page_data['period_end_date'] = $period_end_date;
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = $page_title;
		$this->load->view('backend/index', $page_data);
	}
	
	
	function view_budget_scroll($param1="",$param2="",$param3=""){

		$selected_date = strtotime($param3." months",$param2);
		
		if($param2=="") $selected_date = strtotime(date('Y-m-d'));	
				
		$budget_section_id = $this->db->get_where('budget_section',array('name'=>get_phrase($param1)))
		->row()->budget_section_id;
		
		extract($this->costing($selected_date,$budget_section_id));
		
		$page_title = get_phrase($param1);

		$budget_section_id =  $this->db->get_where('budget_section',array('name'=>$page_title))
		->row()->budget_section_id;
		
		$budget_section_fields = $this->db->get_where('budget_section_field',
		array('budget_section_id'=>$budget_section_id))->result_object();
		
		
		$page_data['scroll_count'] = $param3;
		$page_data['selected_date'] = $date;
		$page_data['current_month'] = $param2;
		$page_data['budget_type'] = $param1;
		$page_data['budget_section_fields'] = $budget_section_fields;		
		$page_data['grouped'] = $grouped;
		$page_data['month'] = $month_count; 
		$page_data['first_month_day'] = $date;
		$page_data['period_start_date'] = $period_start_date;
		$page_data['period_end_date'] = $period_end_date;
		$page_data['page_title'] = $page_title;
		
		echo $this->load->view('backend/budget/view_budget',$page_data,true);
	}
		
	function sof(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('S.O.F'));

		/**Select Category Table**/
		$crud->set_table('sof');
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));

		$output = $crud->render();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);	
	}

	function upload_sof(){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
            
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);		
	}
	
	function review_sof_uploaded($param1="",$template="sof_template"){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

	  		if ($param1 == 'import_excel')
	  		{			
	  			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/excel/'.$template.'.xlsx');
	  			
	  			include 'Simplexlsx.class.php';
				//include APPPATH.'controllers/Simplexlsx.class.php';
	
	  			$xlsx = new SimpleXLSX('uploads/excel/'.$template.'.xlsx');
	
	  			//list($num_cols, $num_rows) = $xlsx->dimension();
				
	  		}
			
		$page_data['uploaded_data'] = $xlsx->rows();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}
	
	function upload_reviewed_sof_data(){
		$sof_array = $this->input->post();
		
		$rows = array();
		
		$sofs = array();
		
		for($i=0;$i<sizeof($sof_array['sof_code']);$i++){

			$rows[$i]['sof_code'] 		= $sof_array['sof_code'][$i];
			$rows[$i]['sof_name'] 		= $sof_array['sof_name'][$i];
			$rows[$i]['start_date'] 	= $sof_array['start_date'][$i];
			$rows[$i]['end_date'] 		= $sof_array['end_date'][$i];
			$rows[$i]['dea_code'] 		= $sof_array['dea_code'][$i];
			$rows[$i]['description'] 	= $sof_array['description'][$i];
			$rows[$i]['office_code'] 	= $sof_array['office_code'][$i];
			$rows[$i]['budget_section_id'] 	= $sof_array['budget_section'][$i];
			$rows[$i]['dea_amount'] 	= $sof_array['dea_amount'][$i];
		}
		
		$deas = array(); 
		
		//$deas_batch_array = array();
		
		foreach($rows as $row){		
			
			$sofs[$row['sof_code']]['sof_code'] 	= $row['sof_code'];	
			$sofs[$row['sof_code']]['sof_name'] 	= $row['sof_name'];
			$sofs[$row['sof_code']]['start_date'] 	= $row['start_date'];
			$sofs[$row['sof_code']]['end_date'] 	= $row['end_date'];
			
			foreach($rows as $dea){
				// $deas[$dea['sof_code']][$dea['dea_code']]['dea_code'] 	= 	$dea['dea_code'];
				// $deas[$dea['sof_code']][$dea['dea_code']]['office_code'] = 	$dea['office_code'];
				// $deas[$dea['sof_code']][$dea['dea_code']]['dea_amount'] 	= 	$dea['dea_amount'];
				
				$sofs[$dea['sof_code']]['dea'][$dea['dea_code']]['dea_code'] 	= 	$dea['dea_code'];
				$sofs[$dea['sof_code']]['dea'][$dea['dea_code']]['description'] = 	$dea['description'];
				$sofs[$dea['sof_code']]['dea'][$dea['dea_code']]['budget_section_id'] = 	$dea['budget_section_id'];
				$sofs[$dea['sof_code']]['dea'][$dea['dea_code']]['office_code'] = 	$dea['office_code'];
				$sofs[$dea['sof_code']]['dea'][$dea['dea_code']]['dea_amount'] 	= 	$dea['dea_amount'];
			}
			
		}
		
		foreach($sofs as $sof){
			if(is_numeric($sof['sof_code'])){
			$data['sof_code'] = $sof['sof_code'];
			$data['name'] = $sof['sof_name'];
			$data['start_date'] = $sof['start_date'];
			$data['end_date'] = $sof['end_date'];
			
			//Check if sof exists
			$count_of_sofs = $this->db->get_where('sof',array('sof_code'=>$sof['sof_code']));
			
			$sof_id = 0;
			
			if($count_of_sofs->num_rows() > 0){
				$sof_id = $count_of_sofs->row()->sof_id;
				$this->db->where(array('sof_code'=>$sof['sof_code']));
				$this->db->update('sof',$data);
			}else{
				$this->db->insert('sof',$data);
				$sof_id = $this->db->insert_id();
			}
			
			//$sof_id = $this->db->insert_id();
			
			$insert_dea_array = array();
			
			foreach($sof['dea'] as $dea){
				//Check if dea exists
				$count_dea = $this->db->get_where('dea',array('dea_code'=>$dea['dea_code']));
				
				$insert_dea_array['sof_id'] = $sof_id;
				$insert_dea_array['dea_code'] = $dea['dea_code'];
				$insert_dea_array['description'] = $dea['description'];
				$insert_dea_array['budget_section_id'] = $dea['budget_section_id'];
				$insert_dea_array['office_id'] = $this->db->get_where('office',array('office_code'=>$dea['office_code']))->row()->office_id;
				$insert_dea_array['initial_amount'] = $dea['dea_amount'];
				
				if($count_dea->num_rows() > 0 ){
					$this->db->where(array('dea_code'=>$dea['dea_code']));
					$this->db->update('dea',$insert_dea_array);
				}else{
					$this->db->insert('dea',$insert_dea_array);
				}
				
			}
			}
		}
		
		
		echo "Success";//json_encode($deas);
	}
	
	function dea(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('DEA'));

		/**Select Category Table**/
		$crud->set_table('dea');
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		
		/**Relationships**/
		$crud->set_relation("office_id", "office", "name");
		$crud->set_relation("sof_id", "sof", "name");
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		
		$crud->display_as("sof_id",get_phrase("SOF_name"))
		->display_as("office_id",get_phrase("office_name"));

		$output = $crud->render();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}
	
	function add_allocation_to_account_staff_record($accounts,$year,$office_id = ""){
		
		$this->db->select(array('budget_id','allocation.dea_id','amount'));
		$this->db->join('dea','dea.dea_id = allocation.dea_id');
		$allocations_for_the_year = $this->db->get_where('allocation',array('alloc_year'=>$year,'dea.office_id'=>$office_id));
		
		$accounts_with_dea = array();
		
		if($allocations_for_the_year->num_rows() > 0){
			
			foreach($accounts as $account){
				$add_allocations = array();
				foreach($allocations_for_the_year->result_object() as $dea){
					if($account->budget_id == $dea->budget_id){
						$add_allocations[$dea->dea_id] = $dea->amount;// Check the old allocation script and make the adjustment
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

	function get_month_actual($month = ''){
		$this->db->select(array('dea_id','month_actual'));
		$bva_update_raw = $this->db->get_where('bva_update',array('update_month '=>$month))->result_array();
		
		$dea_id = array_column($bva_update_raw, 'dea_id');
		$month_actual = array_column($bva_update_raw, 'month_actual');
		
		$bva_update = array_combine($dea_id, $month_actual);
		
		return $bva_update;
	}
	
	function get_dea_grand_allocation($year = ''){
		$this->db->select(array('dea_id'));
		$this->db->select_sum('amount');
		$this->db->group_by('dea_id');
		$bva_update_raw = $this->db->get_where('allocation',array('alloc_year '=>$year))->result_array();
		
		$dea_id = array_column($bva_update_raw, 'dea_id');
		$amount = array_column($bva_update_raw, 'amount');
		
		$dea_grand_allocation = array_combine($dea_id, $amount);
		
		return $dea_grand_allocation;
	}
	
	function get_month_expenses($month = ''){
		
		$start_day = date("Y-m-01",strtotime($month));
		$end_day = date("Y-m-t",strtotime($month));
		
		$this->db->select(array('dea_id'));
		$this->db->select_sum('amount');
		$this->db->group_by('dea_id');
		$bva_update_raw = $this->db->get_where('expense',array('month >= '=>$start_day,'month <= '=> $end_day))->result_array();
		
		$dea_id = array_column($bva_update_raw, 'dea_id');
		$amount = array_column($bva_update_raw, 'amount');
		
		$month_expenses = array_combine($dea_id, $amount);
		
		return $month_expenses;
	}
	
	function allocate_dea_spread($office_id="",$budget_type="",$first_day_of_the_year_epoch="",$last_day_of_the_year_epoch=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
	
		/**
		 * This code is a repeat to allocate_dea method
		 */
		//$first_day_of_the_year_epoch = strtotime(date('Y-m-01')); 
		$budget_section = $this->db->get_where('budget_section',array('short_name'=>$budget_type))->row();		
		
		$section_id = $budget_section->budget_section_id;
		 		
		$table = $budget_section->related_table;
 		
		$first_day_of_the_year = date('Y-m-01',strtotime('first day of january',$first_day_of_the_year_epoch));
		$last_day_of_the_year = date('Y-m-t',strtotime('last day of december',$first_day_of_the_year_epoch));
 		
		$latest_forecast = $this->get_lasted_year_forecast(date('Y',$first_day_of_the_year_epoch));
		
		$where_string = "start_date >= '".$first_day_of_the_year."' AND 
		end_date <= '".$last_day_of_the_year."' AND budget_section_id = ".$section_id." 
		AND forecast_period = ".$latest_forecast;
		
		
		$this->db->where($where_string);
		$this->db->select(array($table.'_id',$table.'.name',$table.'_code','budget.office_code',
		'budget.budget_id','start_date'));
		$this->db->select_sum('budget_spread.amount');
		$this->db->join("budget","budget.related_table_primary_key_value=".$table.".".$table."_id");
		$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
		$this->db->group_by('budget_spread.budget_id');
		$account = $this->db->get_where($table,array('budget.office_code'=>$office_id))
		->result_object();
 		
		//$page_data['testing'] = $account;
 		
		$year = date('Y',$first_day_of_the_year_epoch);
 		
		$records = $this->add_allocation_to_account_staff_record($account,$year,$office_id);
		 
		$active_deas = $this->group_active_deas_by_sof($first_day_of_the_year_epoch,$office_id, $section_id);
		
		//Check if scrolled year is same as current year
		$is_same_year = 0;
		if(date('Y',$last_day_of_the_year_epoch) == date('Y',$first_day_of_the_year_epoch)){
			$is_same_year = 1;
		}	
		
	
		$page_data['bva_update'] = $this->get_month_bva_update($this->get_latest_bva_start_date(),$office_id,$section_id);
		$page_data['latest_bva_update'] = $this->get_latest_bva_start_date();
		$page_data['office_id'] = $office_id;
		$page_data['table'] = $table; 
		$page_data['records'] = $records;
		$page_data['active_deas'] = $active_deas;
		$page_data['budget_type'] = $budget_type;
		$page_data['office_id'] = $office_id;
		$page_data['is_same_year'] = $is_same_year;
		$page_data['start_date'] = $first_day_of_the_year;
		$page_data['first_day_of_the_year_epoch'] = $first_day_of_the_year_epoch;
		
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__).": ".ucwords(str_replace("_", " ", $budget_type));
		$this->load->view('backend/index', $page_data);
	}
	
	function allocate_dea($office_id="",$budget_type="",$selected_date_epoch="",$current_date_epoch=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		$budget_section = $this->db->get_where('budget_section',array('short_name'=>$budget_type))->row();		
		
		$section_id = $budget_section->budget_section_id;
		 		
		$table = $budget_section->related_table;
 		
		$first_day_of_the_year = date('Y-m-01',strtotime('first day of january',$selected_date_epoch));
		$last_day_of_the_year = date('Y-m-t',strtotime('last day of december',$selected_date_epoch));
 		
		$where_string = "start_date >= '".$first_day_of_the_year."' AND 
		end_date <= '".$last_day_of_the_year."' AND budget_section_id = ".$section_id;
				
		/**
		 * Make this universal - This code applies for staff cost only (This can been corrected but not tested)
		 */
		
		$this->db->where($where_string);
		$this->db->select(array($table.'_id',$table.'.name',$table.'_code','budget.office_code',
		'budget.budget_id','start_date'));
		$this->db->select_sum('budget_spread.amount');
		$this->db->join("budget","budget.related_table_primary_key_value=".$table.".".$table."_id");
		$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
		$this->db->group_by('budget_spread.budget_id');
		$account = $this->db->get_where($table,array('budget.office_code'=>$office_id))
		->result_object();
 		
		$year = date('Y',$selected_date_epoch);
 		
		$records = $this->add_allocation_to_account_staff_record($account,$year);
		
		/**
		 * Make this universal
		 */
		 
		$active_deas = $this->group_active_deas_by_sof($selected_date_epoch,$office_id);
		
		//Check if scrolled year is same as current year
		$is_same_year = 0;
		if(date('Y',$current_date_epoch) == date('Y',$selected_date_epoch)){
			$is_same_year = 1;
		}
		
		$page_data['office_id'] = $office_id;
		$page_data['table'] = $table; 
		$page_data['records'] = $records;
		$page_data['active_deas'] = $active_deas;
		$page_data['budget_type'] = $budget_type;
		$page_data['office_id'] = $office_id;
		$page_data['is_same_year'] = $is_same_year;
		$page_data['start_date'] = $first_day_of_the_year;
		
		$allocate_view = $this->load->view('backend/budget/allocate_dea',$page_data);
	}
	
	function show_accounts_allocation($office_id,$start_date,$budget_type){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		$related_table = 'staff';
		
		if($budget_type !== 'staff_cost') $related_table = 'budget_account';
		
		$page_data['related_table'] = $related_table;
		$page_data['budget_type'] = $budget_type;
		$page_data['office_id'] = $office_id; 
		$page_data['start_date'] = $start_date; 
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}

	function check_dea_allocation($dea_id="",$budget_id="",$year=""){
		
		$this->db->where(array('dea_id'=>$dea_id,"budget_id"=>$budget_id,'alloc_year'=>$year));
		$result = $this->db->get('allocation')->result_object();
		
		echo json_encode($result);
	}
	
	function update_dea_from_spread(){
		//Check if record exists
		$records_exists = $this->db->get_where('allocation',
		array('budget_id'=>$_POST['budget_id'],'dea_id'=>$_POST['dea_id'],
		'alloc_year'=>$_POST['alloc_year']))->num_rows();
		
		if($records_exists == 0){
			
			$this->db->insert('allocation',$this->input->post());
		}else{
			$this->db->where(array('budget_id'=>$_POST['budget_id'],'dea_id'=>$_POST['dea_id'],
				'alloc_year'=>$_POST['alloc_year']));
			$data['amount'] = $this->input->post('amount');
			$this->db->update('allocation',$data);
		}
		
	}

	function update_dea_allocation($section,$month){
		//echo json_encode($this->input->post());
		$post = $this->input->post();
		
		//Check if DEA ID for the year exists
		$check_if_dea_exists = $this->db->get_where('allocation',
		array('alloc_year'=>$post['alloc_year'],'budget_id'=>$post['budget_id'],'dea_id'=>$post['dea_id']));
		
		if($check_if_dea_exists->num_rows() == 0){
			$this->db->insert('allocation',$post);
		}else{
			$this->db->where(array('alloc_year'=>$post['alloc_year'],'budget_id'=>$post['budget_id'],
			'dea_id'=>$post['dea_id']));
			$this->db->update('allocation',$post);
		}
		
		
		
		//echo $this->db->affected_rows();
		$this->load_office_budget($section,$month);
		
		echo $this->load->view('backend/budget/view_budget_office_update',$page_data,TRUE);
		
	}
	
	function load_office_budget($param1="",$param2=""){
		
		$selected_date = strtotime($param2);
		
		if($param2=="") $selected_date = strtotime(date('Y-m-d'));	
				
		$budget_section_id = $this->db->get_where('budget_section',array('name'=>get_phrase($param1)))
		->row()->budget_section_id;
		
		extract($this->costing($selected_date,$budget_section_id));
		
		$page_title = get_phrase($param1);

		$budget_section_id =  $this->db->get_where('budget_section',array('name'=>$page_title))
		->row()->budget_section_id;
		
		$budget_section_fields = $this->db->get_where('budget_section_field',
		array('budget_section_id'=>$budget_section_id))->result_object();
		$page_data['selected_date'] = $date;
		$page_data['budget_type'] = $param1;
		$page_data['budget_section_fields'] = $budget_section_fields;		
		$page_data['grouped'] = $grouped;
		$page_data['month'] = $month_count; 
		$page_data['first_month_day'] = $date;
		$page_data['period_start_date'] = $period_start_date;
		$page_data['period_end_date'] = $period_end_date;
        $page_data['page_title'] = $page_title;
		
		return $page_data;
	}
	
	function upload_budget($param1=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		
		$page_data['budget_type'] = $param1; 
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}
	
	function review_budget_bulk_add($param1 = '',$template='')
  	{
  		
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

  		if ($param1 == 'import_excel')
  		{			
  			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/excel/'.$template.'_update_template.xlsx');
  			
  			include 'Simplexlsx.class.php';
			//include APPPATH.'controllers/Simplexlsx.class.php';

  			$xlsx = new SimpleXLSX('uploads/excel/'.$template.'_update_template.xlsx');

  			//list($num_cols, $num_rows) = $xlsx->dimension();
			unlink('uploads/excel/'.$template.'_update_template.xlsx');	
  		}
				
		$page_data['budget_type'] = $template;
		//$page_data['budget_section'] = $this->input->post('budget_section_id');
		$page_data['uploaded_data'] = $xlsx->rows();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
		
  		
  	}

	function upload_reviewed_data($param1=""){
		//Assign the posted grid to a variable	
		$reviewed_upload = $this->input->post();
		
		//A holder of header information
		$header = array();
		
		//A holder of month's spread dat
		$spread = array();
		
		//Count of created records initialized
		$count_created = 0;
		
		//Count of update records initialized
		$count_updated = 0;
		
		//Total number of affected records i.e. created or updated
		$total_affected_records = 0;
		
		/**
		 * Budget type item code e.g. for staff cost budget this is staff_code (Corresponds to staff_code in the staff table), 
		 * thematic/ non thematic cost is budget_account_code (Corresponds to budget_account_code of the budget_account table)		 
		 * */
		$table_code_field = "";
		
		//Primary key of the of the staff or budget_account of the record being uploaded.
		$table_primary_key_field = "";
		
		/** 
		 * Intended to get the name of the table holding the units of the budget e.g. Units can be 
		 * staff or budget_account. The variable below return staff or budget_account
		 * */
		$table = $this->db->get_where('budget_section',array('short_name'=>$param1))
		 ->row()->related_table;
		
		/**
		 * Get the budget_section_id of the budget type from the budget_section table.
		 * It returns 1 for staff cost budget, 2 for themaic cost and 3 for non thematic cost budget
		 */
		  
		$budget_section_id = $this->db->get_where('budget_section',array('short_name'=>$param1))
			 ->row()->budget_section_id;
 			
		/**
		 * This equals the field name that has the code of the unit 
		 * e.g. staff_code or budget_account_code 
		 */
		
		$table_code_field = $table."_code";
		
		/**
		 * Gives the primary key field name of the budget units table. Can either be staff_id or
		 * budget_account_id
		 */
			
		$table_primary_key_field = $table."_id";
		
		/**
		 * Get the size of the records to be uploaded. 
		 * Note outer keys of the post array are the headers of the review grid. Each has a size equaling
		 * to the number of rows in the grid. The choice of month 1 is a coincidence but all column name
		 * ca be used.
		 * **/
		 
		for($i=0;$i<sizeof($reviewed_upload['month_1']);$i++){
 			
			if(isset($reviewed_upload[$table.'_code'][$i])){
					/**
					 * Checks if a staff or budget_account code is existing in the budget.
					 * Only create budget items for existing staff or account codes 
					 */
					if($this->db->get_where($table,array($table.'_code'=>$reviewed_upload[$table.'_code'][$i]))->num_rows()>0)
					 {
						/**
						 * Get the value of the staff_code ot budget_account_code of the current 
						 * looped record
						 */	
						$table_code = $this->db->get_where($table,array($table.'_code'=>$reviewed_upload[$table.'_code'][$i]))->row()->$table_code_field;
						$related_table_primary_key_value = $this->db->get_where($table,array($table_code_field=>$table_code))->row()->$table_primary_key_field;
						
						/**
						 * Get the global_key,forecast_period, description, start date ad end date of 
						 * the current looped record
						 */
						$global_key 		= $reviewed_upload['global_key'][$i];
						$forecast_period 	= $reviewed_upload['forecast_period'][$i];
						$description 		= $reviewed_upload['description'][$i];
						$start_date 		= $reviewed_upload['start_date'][$i];
						$end_date 			= $reviewed_upload['end_date'][$i];
						
						/**
						 * Extract month count of the start and end date as 1,2,3, ... 12
						 * Eliminates the leading zeros
						 */
						$start_month 	= date("n",strtotime($start_date));
						$end_month 		= date("n",strtotime($end_date));
		 				
						/**
						 * Set the default office_id to 0.
						 * Checks if the office_code is provided and get the office_id from the office table
						 * If the budget being uploaded is staff cost then the office_id is obtained from 
						 * Staff table
						 */
						$office_id = 0;
						
						if(isset($reviewed_upload['office_code'][$i])){
		 					$office_id = $this->db->get_where('office',array('office_code'=>$reviewed_upload['office_code'][$i]))->row()->office_id;
						}elseif($table == 'staff'){
							$office_id = $this->db->get_where($table,array($table.'_code'=>$reviewed_upload[$table.'_code'][$i]))->row()->office_id;
						}
						
					
		 				
						/**
						 * 
						 * Scenario: 
						 * 
						 * Start Date: 1/1/2019
						 * Staff/ Account Code: 202020
						 * Office Code: 1200
						 * Budget Section: Staff Cost
						 * Forecast Period: 0
						 * Global_Key = 0
						 * 
						 * a) Checks if budget line as a global key not equal to zero.
						 * b) Checks this record in the database if it exists and has the same forecast_period
						 * 		If yes, it updates the record description, start and end date and month spread
						 * 		If not, it creates a new record new budget line record
						 * c) If the budget line has global key equals to 0, create a new budget line and it's
						 * spread. 
						 *  
						 */
						 
						 /**
						  * Old string at enhancement sprint P1.01
						  * **/
						 
						// $query_string = "YEAR(start_date) = '".date('Y',strtotime($start_date))."' AND  
						// related_table_primary_key_value = ".$related_table_primary_key_value." AND 
						// budget_section_id=".$budget_section_id." AND office_code = ".$office_id." 
						 // AND description = '".$description."'";
						 
						 /**
						  * You can only use global key for this query string but the additional filters 
						  * are used just to be sure, the record truely is not existing
						  * 
						  * Note: The following values of the uploaded record should match what is in 
						  * the database to a record to be considered for update (All MUST be fulfilled):
						  * a) The year of the start date 
						  * b) Same staff or budget account
						  * c) Same budget type e.g. staff, thematic
						  * d) Same office 
						  * e) Same global key
						  * f) Same Forecast Period
						  * 
						  */
						 $query_string = "YEAR(start_date) = '".date('Y',strtotime($start_date))."' AND  
						related_table_primary_key_value = ".$related_table_primary_key_value." AND 
						budget_section_id=".$budget_section_id." AND office_code = ".$office_id." 
						 AND global_key = '".$global_key."' AND forecast_period = '".$forecast_period."'";
						 
		 				$this->db->join($table,"$table.$table_primary_key_field=budget.related_table_primary_key_value");
						$this->db->where($query_string);
		 				
						/**
						 * Check number of rows meeting the filters created above. Insert if not met otherwise update.
						 */
						$budget_rows = $this->db->get('budget')->num_rows();
						
						if($budget_rows == 0){
							
						/**
						 * Populating the header array to be used to create a record in 
						 * the budget table
						 */
							$header['global_key'] = substr( md5($office_id.'-'.$start_date.'-'.$description."-".rand(1000,2000000) ) , 0,15);
							$header['forecast_period'] = $forecast_period;
							$header['budget_section_id'] = $budget_section_id;
							$header['office_code'] = $office_id;
							$header['related_table_primary_key_value'] 	= $related_table_primary_key_value;
							$header['description'] = $description;
							$header['start_date'] 	= $start_date;
							$header['end_date'] 	= $end_date;
							
							//Insert the table header information in budget table		
							$this->db->insert("budget",$header);
		 					
		 					//Get the budget_id of the last inserted record
							$budget_id = $this->db->insert_id();
		 					
							//Loop months spread and insert then budget spread table
							for($j=1;$j<13;$j++){
								 $spread['month'] 			= $j;
								 $spread['budget_id']		= $budget_id;
								 $spread['amount']			= isset($reviewed_upload['month_'.$j][$i])?$reviewed_upload['month_'.$j][$i]:0;
		 						
								 $this->db->insert("budget_spread",$spread);
							}
		 					
							$count_created++;
							$total_affected_records++;
							
						}else{
							/**
							 * Populate updateable headers
							 */
							$header['description'] = $description;
							$header['start_date'] 	= $start_date;
							$header['end_date'] 	= $end_date;
							
							///Update header information
							$this->db->where($query_string);
							$this->db->update('budget',$header);
						  	
							//Get the budget id based on the created query string				
							$this->db->where($query_string);
							$budget_id = $this->db->get('budget')->row()->budget_id;
		 					
							//Loop to update update the months spread and insert in budget spread table
							for($j=$start_month;$j<$end_month+1;$j++){
								if(isset($reviewed_upload['month_'.$j][$i])){
									$spread['amount']		= isset($reviewed_upload['month_'.$j][$i])?$reviewed_upload['month_'.$j][$i]:0;
									$this->db->where(array('budget_id'=>$budget_id,'month'=>$j));
									$this->db->update("budget_spread",$spread);	
								}
								
							}
							
							$count_updated++;
							$total_affected_records++;
		 					
						}				
					}
				}	
					
		}
		//$count_updated = $start_month." - ".$end_month;
		if($total_affected_records>0){
			echo "You have ".$total_affected_records." affected records: \n";//sizeof($reviewed_upload[$table_code_field])
			echo $count_created." records created \n".$count_updated." records updated";
			//print_r($header);
		}else{
			echo "Error: Staff/ Account Codes not available or wrong fields in the template";
		}
		
		
	}



	function add_budget_line($param1="",$param2=1){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		extract($this->costing($param1,$param2));
		
		$related_table_ids_budgeted_for = $this->get_budgeted_related_table_ids_for_the_year($grouped);
		
		$page_data['staff'] = $this->get_staff(); 
		$page_data['budgeted_staff'] = $related_table_ids_budgeted_for;
		$page_data['period_start_date'] = $period_start_date;
		$page_data['period_end_date'] = $period_end_date;
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);		
	}

	function edit_budget_line($param1=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
		$this->db->join('budget_section','budget_section.budget_section_id=budget.budget_section_id');
		$budget_line = $this->db->get_where('budget',array('budget.budget_id'=>$param1))->result_array();
		
		$page_data['staff'] = $this->get_staff(); 
		$page_data['budget_line'] = $budget_line;
		$page_data['view_type']  = "budget";
		$page_data['budget_id']  = $param1;
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);		
	}		
	
	function get_staff(){
		$this->db->select(array('staff_id','name','staff_code','office_id','role_id'));
		$staff_raw_results = $this->db->get('staff')->result_object();//$this->crud_model->get_type_results_as_array("staff");
		
		$grouped_by_office = array();
		
		foreach($staff_raw_results as $staff){
			$grouped_by_office[$staff->office_id][] = $staff;
		}
		
		return $grouped_by_office;
	}
	
	
	
	function update_budget_line($param1="",$param2=""){
		$post = $this->input->post();
		$total = array_pop($post);
		$spread = array_pop($post);
		$header = $post;
		
		$budget_id = 0;
		
		if($param1 == 'insert'){
			$this->db->insert("budget",$header);
			$budget_id = $this->db->insert_id();
		}else{
			$this->db->where(array('budget_id'=>$param2));
			$this->db->update("budget",$header);
			$budget_id = $param2;
		}
		
		
		$spread_batch = array();
		
		for($i=0;$i<count($spread);$i++){
			$spread_batch[$i]['budget_id'] 	= $budget_id;
			$spread_batch[$i]['month'] 		= $i+1;
			$spread_batch[$i]['amount']		= $spread[$i];
		}
		if($param1 == 'insert'){
			$this->db->insert_batch("budget_spread",$spread_batch);
		}else{
			$this->db->where(array('budget_id'=>$param2));
			$this->db->update_batch("budget_spread",$spread_batch,'month');
		}
		
		//echo json_encode($spread_batch);
		
		if($this->db->affected_rows()>0){
			echo "Success";
		}else{
			echo "No record updated";
		}
	}
	
	public function costing_summary(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('costing_summary'));

		/**Select Category Table**/
		$crud->set_table('costing_summary');

		$output = $crud->render();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}		

	public function budget_themes(){
	  	if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');


			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('budget_themes'));

		/**Select Category Table**/
		$crud->set_table('budget_themes');
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		

		$output = $crud->render();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);		
	}
	
	public function budget_section(){
	  	if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');


			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('budget_section'));

		/**Select Category Table**/
		$crud->set_table('budget_section');
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		

		$output = $crud->render();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);		
	}	
	
	function post_allocation(){
		
		
		$msg = "Error on creating or update occurred";
		
		/**Check if sread is allocated**/
		$check_alloc = $this->db->get_where("allocation",
		array("budget_id"=>$_POST['budget_id'],"dea_code"=>$_POST['dea_code']));
		
		if($check_alloc->num_rows()>0){
			/**Update the allocation**/
			$update_data['amount'] = $_POST['amount'];
			$this->db->where(array("budget_id"=>$_POST['budget_id'],"dea_code"=>$_POST['dea_code']));
			$this->db->update("allocation",$update_data);
			if($this->db->affected_rows()> 0) $msg = "Allocation Updated";
		}else{
			/**Insert new allocation**/
			$data['budget_id'] = $_POST['budget_id'];	
			$data['dea_code'] = $_POST['dea_code'];
			$data['amount'] = $_POST['amount'];
			
			$this->db->insert("allocation",$data);
			if($this->db->affected_rows()> 0) $msg = "New Allocation Created";
		}
		
		echo $msg;
		
	}
	
	function expense_updates($param1=""){		
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
           
		 if($param1 == "") $param1 = strtotime(date('Y-m-01'));  
		   
        $this->db->join('dea','dea.dea_id=expense.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');   
		$this->db->select(array('expense_id','office.name as office','sof.sof_code','sof.name as sof','dea.dea_code','expense.month','expense.amount'));
        $month_expenses = $this->db->get('expense')->result_object();  
		
		
		$page_data['month_expenses'] = $month_expenses;   
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);	
	}
	
	function group_active_deas_by_sof($start_date = "",$office_id="", $budget_section_id=""){

		$year_start_date = date('Y-m-01',strtotime('first day of january',$start_date));
		$year_end_date = date('Y-m-t',strtotime('last day of december',$start_date));
		
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');
		$this->db->select(array('dea.dea_id','dea.description','office.name as office','sof.sof_code','sof.name as sof',
		'dea.dea_code'));
		if($office_id!=""){
			$this->db->where(array('dea.office_id'=>$office_id));
		}
		
		if($budget_section_id!=""){
			$this->db->where(array('dea.budget_section_id'=>$budget_section_id));
		}
		
		$active_deas =  $this->db->get_where('dea',
		array('sof.start_date<='=>$year_start_date,'sof.end_date>='=>$year_end_date))->result_object();
		
		$group = array();
		
		foreach($active_deas as $row){
			$group[$row->sof_code.' : '.$row->sof][] = $row;
		} 
		
		return $group;
	}
	
	function add_expense_update($param1 = ""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		if($param1 == "") $param1 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param1);
        
		$page_data['active_deas'] = $active_deas;    
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);			
	}
	
	function insert_expense_update(){
			
		$input = $this->input->post();
		
		$this->db->insert('expense',$input);
		
		if($this->db->affected_rows() > 0){
			echo "Success";
		}else{
			echo "Recorded not inserted";
		}
	}
	
	function edit_expense_update($param1="",$param2=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		$this->db->join('dea','dea.dea_id=expense.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');   
		$this->db->select(array('expense_id','expense.dea_id','office.name as office','sof.sof_code','sof.name as sof','dea.dea_code','expense.month','expense.amount'));
         
		$expense_record = $this->db->get_where('expense',array('expense_id'=>$param1))->row();
		
		if($param2 == "") $param2 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param2);
		
		$page_data['active_deas'] = $active_deas;
		$page_data['expense'] = $expense_record;	
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);			
	}
	
	function update_expense_update($param1=""){
		
		$msg = get_phrase('failed');
		
		$data[0]['expense_id'] = $param1;
		$data[0]['dea_id'] = $this->input->post('dea_id');
		$data[0]['month'] = $this->input->post('month');
		$data[0]['amount'] = $this->input->post('amount');
		
		//$this->db->where(array('expense_id',$param1));
		$this->db->update_batch('expense',$data,'expense_id');
		
		if($this->db->affected_rows() > 0 ){
			$msg = get_phrase('success');
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/expense_updates','refresh');
	}

	
	function delete_expense_update($param1=""){
			
		$this->db->delete('expense',array('expense_id'=>$param1));
		
		$msg = "No record deleted";
		
		if($this->db->affected_rows() > 0){
			$msg = "Record deleted";
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/expense_updates','refresh');
	}
	
	function add_bva_update($param1=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		if($param1 == "") $param1 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param1);
        
		$page_data['active_deas'] = $active_deas;    
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);	
	}
	
	function insert_bva_update(){
		$input = $this->input->post();
		
		$this->db->insert('bva_update',$input);
		
		if($this->db->affected_rows() > 0){
			echo "Success";
		}else{
			echo "Recorded not inserted";
		}
	}
	
	function commitments_updates(){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		$this->db->join('dea','dea.dea_id=commitment.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');   
		$this->db->select(array('commitment_id','office.name as office','sof.sof_code','sof.name as sof',
		'dea.dea_code','commitment.month','commitment.amount'));     
		$commitments = $this->db->get('commitment')->result_object();
			
		$page_data['commitments'] = $commitments;
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase('commitments_tracker');
		$this->load->view('backend/index', $page_data);	
	}
	
	function add_commitment_update($param1=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
           
		if($param1 == "") $param1 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param1);
        
		$page_data['active_deas'] = $active_deas;  
		    
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);		
	}
	
	function edit_commitment_update($param1="",$param2=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		$this->db->join('dea','dea.dea_id=commitment.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');   
		$this->db->select(array('commitment_id','commitment.dea_id','commitment.lpo','commitment.description','office.name as office','sof.sof_code','sof.name as sof','dea.dea_code','commitment.month','commitment.amount'));
         
		$commitment_record = $this->db->get_where('commitment',array('commitment_id'=>$param1))->row();
		
		if($param2 == "") $param2 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param2);
		
		$page_data['active_deas'] = $active_deas;
		$page_data['commitment'] = $commitment_record;	
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}
	
	function update_commitment_update($param1=""){
		$msg = get_phrase('failed');
		
		$data[0]['commitment_id'] = $param1;
		$data[0]['dea_id'] = $this->input->post('dea_id');
		$data[0]['lpo'] = $this->input->post('lpo');
		$data[0]['description'] = $this->input->post('description');
		$data[0]['month'] = $this->input->post('month');
		$data[0]['amount'] = $this->input->post('amount');
		
		//$this->db->where(array('commitment_id',$param1));
		$this->db->update_batch('commitment',$data,'commitment_id');
		
		if($this->db->affected_rows() > 0 ){
			$msg = get_phrase('success');
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/commitments_updates','refresh');
	}
	
	function delete_commitment_update($param1=""){
		$this->db->delete('commitment',array('commitment_id'=>$param1));
		
		$msg = "No record deleted";
		
		if($this->db->affected_rows() > 0){
			$msg = "Record deleted";
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/commitments_updates','refresh');
	}
	
	function insert_commitment_update(){
			
		$input = $this->input->post();
		
		$this->db->insert('commitment',$input);
		
		if($this->db->affected_rows() > 0){
			echo "Success";
		}else{
			echo "Recorded not inserted";
		}
	}

	

	function get_month_bva_update($month_start_date,$office_id="", $budget_section_id=""){
		
		
		/**YTD Actuals for the year grouped by dea**/
		$where_string = "update_month <='".$month_start_date."'";
		if($office_id != "") $where_string .= " AND office_id = ".$office_id." AND budget_section_id = ".$budget_section_id;
		$where_string .= " AND update_month >= '".date('Y-m-01',strtotime('first day of january',strtotime($month_start_date)))."'";
		$this->db->select(array('bva_update.dea_id'));
		$this->db->select('SUM(bva_update.month_actual) as ytd_actual');
		$this->db->group_by('bva_update.dea_id');
		$this->db->where($where_string);
		$this->db->join('dea','dea.dea_id = bva_update.dea_id');
		$ytd_actual = $this->db->get('bva_update')->result_array();
		
		$ytd_actual_amount = array_column($ytd_actual, 'ytd_actual');
		$ytd_actual_dea = array_column($ytd_actual, 'dea_id');
		$dea_keyed_ytd_actual = array_combine($ytd_actual_dea,$ytd_actual_amount);
			
		/**Get Past LOA Actuals from dea initial amount table**/
		$this->db->select(array('dea_id','initial_amount'));
		if($office_id != "") $this->db->where(array('office_id'=>$office_id,'budget_section_id'=>$budget_section_id));
		$past_loa_actual = $this->db->get('dea')->result_array();
		
		$past_loa_actual_amount = array_column($past_loa_actual, 'initial_amount');
		$past_loa_actual_dea = array_column($past_loa_actual, 'dea_id');
		$dea_keyed_past_loa_actual = array_combine($past_loa_actual_dea,$past_loa_actual_amount);
				
		/**LOA Actuals for the year grouped by dea**/
		$where_string = "update_month <='".$month_start_date."'";
		if($office_id != "") $where_string .= " AND office_id = ".$office_id." AND budget_section_id = ".$budget_section_id;
		$this->db->select('SUM(bva_update.month_actual) as loa_actual');
		$this->db->select(array('bva_update.dea_id'));
		$this->db->group_by('bva_update.dea_id');
		$this->db->where($where_string);
		$this->db->join('dea','dea.dea_id = bva_update.dea_id');
		$loa_actual = $this->db->get('bva_update')->result_array();
		
		$loa_actual_amount = array_column($loa_actual, 'loa_actual');
		$loa_actual_dea = array_column($loa_actual, 'dea_id');
		$dea_keyed_loa_actual = array_combine($loa_actual_dea, $loa_actual_amount);
 		
		/**Calculate Expense for the month**/
		$this->db->select('expense.dea_id');
		$this->db->select_sum('amount');
		$this->db->where(array('month>='=>$month_start_date,'month<='=>date('Y-m-t',strtotime($month_start_date))));
		if($office_id != "")  $this->db->where(array('office_id'=>$office_id,'budget_section_id'=>$budget_section_id));
		$this->db->group_by('expense.dea_id');
		$this->db->join('dea','dea.dea_id = expense.dea_id');
		$month_expense = $this->db->get('expense')->result_array();
		
		
		$expense_amount = array_column($month_expense, 'amount');
		$expense_dea = array_column($month_expense, 'dea_id');
		$dea_keyed_expense = array_combine($expense_dea, $expense_amount);
		
	
		/**Calculate Commitment for the month**/
		$this->db->select('commitment.dea_id');
		$this->db->select_sum('amount');
		if($office_id != "")  $this->db->where(array('office_id'=>$office_id,'budget_section_id'=>$budget_section_id));
		$this->db->group_by('commitment.dea_id');
		$this->db->join('dea','dea.dea_id = commitment.dea_id');
		$month_commitment = $this->db->get('commitment')->result_array();
		
		$commitment_amount = array_column($month_commitment, 'amount');
		$commitment_dea = array_column($month_commitment, 'dea_id');
		$dea_keyed_commitment = array_combine($commitment_dea, $commitment_amount);
		
		/**Budget Allocated ammount**/
		$latest_forecast = $this->get_lasted_year_forecast(date('Y',strtotime($month_start_date)));
		$where_string = "alloc_year = YEAR('".$month_start_date."') AND forecast_period = ".$latest_forecast;
		$this->db->select_sum('amount');
		$this->db->select('allocation.dea_id');
		$this->db->join('dea','dea.dea_id = allocation.dea_id');
		$this->db->join('budget','budget.budget_id=allocation.budget_id');	
		if($office_id != "")  $this->db->where(array('dea.office_id'=>$office_id,'dea.budget_section_id'=>$budget_section_id));
		$this->db->where($where_string);
		$this->db->group_by('allocation.dea_id');
		$allocation = $this->db->get('allocation')->result_array();
		
		$allocation_amount = array_column($allocation, 'amount');
		$allocation_dea = array_column($allocation, 'dea_id');
		$dea_keyed_allocation = array_combine($allocation_dea, $allocation_amount);
		
		
		//Form a combined array for YTD Actuals, Past LOA Actuals, LOA Actuals, Expense and Commitment
		$combined_array = array();
		$combined_array['ytd_actuals'] = $dea_keyed_ytd_actual;
		$combined_array['initial_loa_actuals'] = $dea_keyed_past_loa_actual;
		$combined_array['loa_actuals'] = $dea_keyed_loa_actual;
		$combined_array['expenses'] = $dea_keyed_expense;
		$combined_array['commitments'] = $dea_keyed_commitment;
		$combined_array['ytd_allocations'] = $dea_keyed_allocation;
				
		/**Current month BVA Update**/
		
		// $this->db->select(array('bva_update_id','description','office.name as office','sof.sof_code',
		// 'sof.name as sof','dea.dea_code','dea.dea_id','bva_update.update_month','bva_update.month_actual',
		// 'month_forecast','ytd_forecast','year_forecast','loa_forecast','dea.initial_amount'));
		
		$this->db->select(array('dea.dea_id','bva_update.update_month','bva_update.month_actual',
		'month_forecast','ytd_forecast','year_forecast','loa_forecast'));
		
		$this->db->where(array('update_month'=>$month_start_date));
		if($office_id != "")  $this->db->where(array('dea.office_id'=>$office_id,
		'budget_section_id'=>$budget_section_id));		
		if($office_id != "")  $this->db->where(array('dea.office_id'=>$office_id,'budget_section_id'=>$budget_section_id));
		$this->db->join('dea','dea.dea_id=bva_update.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id'); 
		
        $bva_updates = $this->db->get('bva_update')->result_array(); 
		
		//Construct month actual, month forecast, ytd forecast, year forecast, loa forecast a
		
		if(count($bva_updates) > 0){
			foreach(array_keys($bva_updates[0]) as $column){
	
				if($column == 'dea_id' || $column == 'update_month') continue;
				
				$amount = array_column($bva_updates, $column);
				$dea = array_column($bva_updates, 'dea_id');
				$dea_keyed = array_combine($dea, $amount);
				
				$combined_array[$column] = $dea_keyed;
			}
		}
		
		
		//Compute LOA DEA Balance array
		$dea_keyed_loa_dea_balance = array();
		foreach(array_keys($dea_keyed_past_loa_actual) as $dea_id){
			$loa_forecast = isset($combined_array['loa_forecast'][$dea_id])?$combined_array['loa_forecast'][$dea_id]:0;
			$initial_loa_actuals = isset($dea_keyed_past_loa_actual[$dea_id])?$dea_keyed_past_loa_actual[$dea_id]:0;
			$loa_actuals = isset($dea_keyed_loa_actual[$dea_id])?$dea_keyed_loa_actual[$dea_id]:0;
			$sum_of_loa_actuals = $initial_loa_actuals + $loa_actuals;
			$dea_keyed_loa_dea_balance[$dea_id] = $loa_forecast - $sum_of_loa_actuals;
		}

		$combined_array['loa_dea_balance'] = $dea_keyed_loa_dea_balance;
		
		
		//Compute Full Year DEA Balance array
		$dea_keyed_year_dea_balance = array();
		foreach(array_keys($dea_keyed_past_loa_actual) as $dea_id){
			$year_forecast = isset($combined_array['year_forecast'][$dea_id])?$combined_array['year_forecast'][$dea_id]:0;
			//$initial_loa_actuals = isset($dea_keyed_past_loa_actual[$dea_id])?$dea_keyed_past_loa_actual[$dea_id]:0;
			$year_actuals = isset($dea_keyed_ytd_actual[$dea_id])?$dea_keyed_ytd_actual[$dea_id]:0;
			//$sum_of_loa_actuals = $initial_loa_actuals + $loa_actuals;
			$dea_keyed_year_dea_balance[$dea_id] = $year_forecast - $year_actuals;
		}

		$combined_array['year_dea_balance'] = $dea_keyed_year_dea_balance;
				
		
		return $combined_array;
		 	
	}

	function get_month_bva_update_grouped_by_period($month_start_date,$office_id="",$budget_section_id=""){
		
		$month_bva_update = $this->get_month_bva_update($month_start_date);
		
		/**Check we there is a bva_update**/
		$bva_records_count = $this->db->get_where('bva_update',array('update_month'=>$month_start_date))->num_rows();
		
		//Check user restriction by sof and office
		$office = $this->stcdea_model->get_restricted_objects($this->session->login_user_id,'office');
		$sof = $this->stcdea_model->get_restricted_objects($this->session->login_user_id,'sof');
		
		/**Get all DEAs**/
		$this->db->select(array('office.name as office','sof.name as sof','sof.sof_code','dea.dea_code','dea.dea_id','dea.description'));
		
		if(count($office)> 0 ) $this->db->where_in('dea.office_id',$office);
		if(count($sof)> 0 ) $this->db->where_in('sof.sof_id',$sof);
		
		$this->db->join('sof','sof.sof_id=dea.sof_id');
		$this->db->join('office','office.office_id=dea.office_id');
		$deas_with_sof_and_office_information = $this->db->get('dea')->result_array();
		
		//Append month bva update to sof information
		
		$deas_with_sof_and_office_information_and_bva_month_updates = array();
		
		$loop = 0;
		foreach($deas_with_sof_and_office_information as $row){
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['dea_information'] = $row;
			
			$month_expenses = isset($month_bva_update['expenses'][$row['dea_id']])?$month_bva_update['expenses'][$row['dea_id']]:0;
			$month_commitments = isset($month_bva_update['commitments'][$row['dea_id']])?$month_bva_update['commitments'][$row['dea_id']]:0;
			
			$expense_and_commitment_sum = $month_expenses + $month_commitments;
			
			$month_actuals = $expense_and_commitment_sum + isset($month_bva_update['month_actual'][$row['dea_id']]) && isset($month_bva_update['month_actual'])?$month_bva_update['month_actual'][$row['dea_id']]:0;
			$month_forecast = isset($month_bva_update['month_forecast'][$row['dea_id']])?$month_bva_update['month_forecast'][$row['dea_id']]:0;
			$month_variance = $month_forecast - $month_actuals;
			$month_per_variance = ($month_forecast != 0)?($month_variance/$month_forecast):0;
				
			$ytd_forecast = isset($month_bva_update['ytd_forecast'][$row['dea_id']])?$month_bva_update['ytd_forecast'][$row['dea_id']]:0;
			$ytd_actuals = $expense_and_commitment_sum + isset($month_bva_update['ytd_actuals'][$row['dea_id']]) && $bva_records_count > 0?$month_bva_update['ytd_actuals'][$row['dea_id']]:0;
			$ytd_variance = $ytd_forecast - $ytd_actuals;
			$ytd_per_variance = ($ytd_forecast != 0)?($ytd_variance/$ytd_forecast):0;

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
			
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['full_year']['forecast'] = $year_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['full_year']['burn_rate'] = $year_burn_rate;
			
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['forecast'] = $loa_forecast;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['actual'] = $loa_actuals;
			$deas_with_sof_and_office_information_and_bva_month_updates[$loop]['loa']['burn_rate'] = $loa_burn_rate;
			
			$loop++;
		}
		
		return $deas_with_sof_and_office_information_and_bva_month_updates;
		
		// $i = 0;
		// foreach($ungrouped_bva_update as $row){
// 			
			// $expense_and_commitment_of_the_month = $row->month_expense +  $row->month_commitment;
			// $month_expense_to_date = $row->month_actual + $expense_and_commitment_of_the_month;
			// $ytd_to_date = $row->ytd_actual + $expense_and_commitment_of_the_month;
			// $loa_to_date = $row->loa_actual + $expense_and_commitment_of_the_month;
// 			
			// $grouped_bva_update[$i]['description'] = array(
				// 'bva_update_id'=>$row->bva_update_id,
				// 'description'=>$row->description,
				// 'office'=>$row->office,
				// 'sof_code'=>$row->sof_code,
				// 'sof'=>$row->sof,
				// 'dea_code'=>$row->dea_code,
				// 'dea_id'=>$row->dea_id,
				// 'update_month'=>$row->update_month);
// 				
			// $grouped_bva_update[$i]['initial'] = $row->initial_amount;
			// $grouped_bva_update[$i]['month'] = array(
				// 'forecast'=>$row->month_forecast,
				// 'actual'=>$month_expense_to_date,
				// 'variance'=>$row->month_forecast - $month_expense_to_date,
				// 'per_variance'=>($row->month_forecast - $month_expense_to_date)/$row->month_forecast);
// 				
			// $grouped_bva_update[$i]['ytd'] = array(
				// 'forecast'=>$row->ytd_forecast,
				// 'actual'=>$ytd_to_date,
				// 'variance'=>$row->ytd_forecast - $ytd_to_date,
				// 'per_variance'=>($row->ytd_forecast - $ytd_to_date)/$row->ytd_forecast);	
// 				
			// $grouped_bva_update[$i]['full_year'] = array(
				// 'forecast'=>$row->year_forecast,
				// 'burn_rate'=>$row->ytd_actual/$row->year_forecast);	
// 				
			// $grouped_bva_update[$i]['loa'] = array(
				// 'forecast'=>$row->loa_forecast,
				// 'actual'=>$loa_to_date,
				// 'burn_rate'=>$loa_to_date/$row->loa_forecast);
// 			
			// $i++;			
		// }
// 		
		//return $grouped_bva_update;
	}
	
	function bva_updates_scroll($param1 = "",$scroll_to = ""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
		
		$current_date  = $param1;
		
		if($param1 == "") $param1 = strtotime(date('Y-m-01'));
		
		if($scroll_to != "") $param1 = strtotime($scroll_to." months",strtotime(date('Y-m-01')));
		
		$month_start_date = date('Y-m-01',$param1);
		
		$bva_updates = $this->get_month_bva_update_grouped_by_period($month_start_date);//$this->get_month_bva_update($month_start_date);
		
		
		$page_data['bva_updates'] 	= $bva_updates; 
        $page_data['month_epoch'] 	= $param1;
		$page_data['scroll_count'] 	= $scroll_to; 
		$page_data['current_month']	= $current_date;
		echo $this->load->view('backend/budget/bva_updates', $page_data,true);	
	}	
	
	//Get start date of the latest uploaded BVA
	function get_latest_bva_start_date(){
		/**
		 * a) First check if there is any BVA record present by returning count
		 * b) If a record exist select maximum  update months of the existing records and get 
		 * row value of update month
		 * c) Return the row value
		 * d) If no record is present the results of (a) get the start date of the current year.
		 * f) Return the results of (c) or (d)
		 */
		 
		 //Returned if there are no BVA records present
		 
		 $start_date = date("Y-m-01");
		 
		 //Check if there are any bva update records present
		 $count_of_bva_records = $this->db->get('bva_update')->num_rows();
		 
		 if($count_of_bva_records > 0){
		 	//Get Max update month date and return it's value
		 	$this->db->select_max('update_month');
			return $results_of_max_date = $this->db->get('bva_update')->row()->update_month;

		 }
		 
		 return $start_date;
	}
	
	function bva_updates($month_start_date = "", $specific_action_trigger = ""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
		
		//Initiate $month_start_date if not passed. Set it to the date of the last bva update
		if($month_start_date == "") $month_start_date = $this->get_latest_bva_start_date();		
		
		if($specific_action_trigger == 'delete'){
			$this->db->where(array('update_month'=>$month_start_date));
			$this->db->delete('bva_update');
			$this->session->set_flashdata('flash_message',get_phrase('records_deleted_successful'));
			redirect(base_url().'budget/bva_updates','refresh');
		}
		
		$bva_updates = $this->get_month_bva_update_grouped_by_period($month_start_date);
		
		$page_data['bva_updates'] = $bva_updates; 
        $page_data['month_epoch'] = strtotime($month_start_date);
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);	
	}		
	
	function edit_bva_update($param1="",$param2=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		$this->db->join('dea','dea.dea_id=bva_update.dea_id'); 
		$this->db->join('sof','sof.sof_id=dea.sof_id'); 
		$this->db->join('office','office.office_id=dea.office_id');   
		$this->db->select(array('bva_update_id','bva_update.dea_id','office.name as office',
		'sof.sof_code','sof.name as sof','dea.dea_code','bva_update.update_month','bva_update.balance'));
         
		$bva_update_record = $this->db->get_where('bva_update',array('bva_update_id'=>$param1))->row();
		
		if($param2 == "") $param2 = strtotime(date('Y-m-01'));
		
        $active_deas = $this->group_active_deas_by_sof($param2);
		
		$page_data['active_deas'] = $active_deas;
		$page_data['bva_update'] = $bva_update_record;	
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}

	function update_bva_update($param1=""){
		$msg = get_phrase('failed');
		
		$data[0]['bva_update_id'] = $param1;
		$data[0]['dea_id'] = $this->input->post('dea_id');
		$data[0]['update_month'] = $this->input->post('update_month');
		$data[0]['balance'] = $this->input->post('balance');
		
		//$this->db->where(array('expense_id',$param1));
		$this->db->update_batch('bva_update',$data,'bva_update_id');
		
		if($this->db->affected_rows() > 0 ){
			$msg = get_phrase('success');
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/bva_updates','refresh');
	}

	function delete_bva_update($param1=""){
		$this->db->delete('bva_update',array('bva_update_id'=>$param1));
		
		$msg = "No record deleted";
		
		if($this->db->affected_rows() > 0){
			$msg = "Record deleted";
		}
		
		$this->session->set_flashdata('flash_message',$msg);
		redirect(base_url().'budget/bva_updates','refresh');
	}

	
	function upload_monthly_update($param1=""){
		if ($this->session->userdata('user_login') != 1)
           redirect(base_url() . 'login', 'refresh');
        
		$page_data['update_view_name'] = $param1;    
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);			
	}
	
	function review_monthly_update_bulk_upload($param1 = '',$template = '')
  	{
  		
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

  		if ($param1 == 'import_excel')
  		{			
  			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/excel/'.$template.'_template.xlsx');
  			
  			include 'Simplexlsx.class.php';
			//include APPPATH.'controllers/Simplexlsx.class.php';

  			$xlsx = new SimpleXLSX('uploads/excel/'.$template.'_template.xlsx');

  			//list($num_cols, $num_rows) = $xlsx->dimension();
			
			unlink( 'uploads/excel/'.$template.'_template.xlsx');
  		}
		
		$page_data['update_type'] = $template;
		$page_data['monthly_update_type'] = $template;
		$page_data['uploaded_data'] = $xlsx->rows();
		$page_data['view_type']  = "budget";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__).": ".get_phrase($template);
		$this->load->view('backend/index', $page_data);
  	}

	function upload_reviewed_monthly_update($param1=""){
		$reviewed_upload = $this->input->post();
		
		$fields_array = array_keys($reviewed_upload);
		
		$data = array();
		
		for($i=0;$i<count($reviewed_upload[$fields_array[0]]);$i++){
			
			foreach($fields_array as $field){
				$field_name = $field;
				$field_value = $reviewed_upload[$field][$i];
				if($field == 'dea_code') {
					$field_name = 'dea_id';
					$field_value_obj = $this->db->get_where('dea',array('dea_code'=>$reviewed_upload[$field][$i]));
					if($field_value_obj->num_rows()>0){
						$field_value = $field_value_obj->row()->dea_id;
					}else{
						$field_value = 0;
					}
				}
				$data[$i][$field_name] = $field_value;
			}
		}
		
		$table = $param1 !== 'bva_update'?str_replace('_update', '', $param1):'bva_update';
		
		/** Expense update is cumulative, remove the previous records uploaded **/
		if($table == 'expense' || $table == 'bva_update' )	$this->db->truncate('expense');
		
		//if($table == 'bva_update') $this->db->truncate('expense');
				
		$this->db->insert_batch($table,$data);

		if($this->db->affected_rows() > 0){
			echo "Success";
		}else{
			echo "No data inserted";
		}
	}
	
	function assign_expense_to_date($allocation,$month,$timeline){
		
		//Start of year date
		$start_of_the_year = date('Y-m-01',strtotime('first day of january',strtotime($month)));
		//$end_of_the_year = date('Y-m-t',strtotime('last day of december',strtotime($month)));
		
		//Month start and end date
		$start_of_the_month = date('Y-m-01',strtotime($month));
		$end_of_the_month = date('Y-m-t',strtotime($month));
		
		//Current BVA Update
				
		$forecast = '';
			
		if($timeline=='full_year'){
			$forecast = 'year_forecast as forecast';
		}elseif($timeline=='loa'){
			$forecast = 'loa_forecast as forecast';
		}else{
			$forecast = 'ytd_forecast as forecast';
		}
		
		$this->db->select_sum('month_actual');
		$this->db->select(array('bva_update.dea_id','dea_code','dea.description','sof.name as sof','sof_code',
		'office.name as office',$forecast));
		$this->db->group_by('bva_update.dea_id');
		$this->db->join('dea','dea.dea_id=bva_update.dea_id');
		$this->db->join('sof','sof.sof_id=dea.sof_id');
		$this->db->join('office','office.office_id=dea.office_id');
		$current_bva = $this->db->get_where('bva_update',
		array('update_month<='=>$end_of_the_month,
		'update_month>='=>$start_of_the_year));		
		
		$assigned = array();
		
		if($current_bva->num_rows() > 0){
			//Construct a merged array of keys DEA_ids and values as balances - Current BVA 
			$current_bva_array = $current_bva->result_array(); 
			$current_bva_deas = array_column($current_bva_array, 'dea_id');
			$current_bva_deas_amounts = array_column($current_bva_array, 'month_actual');
			$current_bva_deas_amounts_merger = array_combine($current_bva_deas, $current_bva_deas_amounts);
			
			//Calculate months expense update
			$string_where =  'month BETWEEN "'.$start_of_the_month.'" AND "'.$end_of_the_month.'"'; 
			$this->db->where($string_where);
			$this->db->select_sum('amount');
			$this->db->select(array('dea_id'));
			$this->db->group_by('dea_id');
			$expense_updates = $this->db->get('expense');
			
			$expense_updates_array = array();
			
			if($expense_updates->num_rows() > 0){
				$dea_ids_with_expense_updates = array_column($expense_updates->result_array(), 'dea_id');
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
				$dea_ids_with_commitment_updates = array_column($commitment_updates->result_array(), 'dea_id');
				$commitment_per_dea_id = array_column($commitment_updates->result_array(), 'amount');
				
				$commitment_updates_array = array_combine($dea_ids_with_commitment_updates, $commitment_per_dea_id);
			}
			
			//Compute expense to date
			$expense_to_date = array();
			foreach(array_keys($current_bva_deas_amounts_merger) as $dea_id){
				$expense_update = 0;
				if(isset($expense_updates_array[$dea_id])){
					$expense_update = $expense_updates_array[$dea_id];
				}
				
				$commitment_update = 0;
				
				if(isset($commitment_updates_array[$dea_id])){
					$commitment_update = $commitment_updates_array[$dea_id];
				}
				
				$expense_to_date[$dea_id] = $expense_update + $commitment_update;
			}	
			
			//Append expense to date to Active DEA records		

			foreach($current_bva->result_array() as $dea_alloc){
					
				$dea_alloc['amount'] = 0;
				$dea_alloc['expense'] = 0;
				
				foreach($allocation as $alloc){
					if($dea_alloc['dea_id'] == $alloc['dea_id']) 
						$dea_alloc['amount'] = $alloc['amount'];
				}
				
				foreach($expense_to_date as $dea_id=>$expense){
					if($dea_alloc['dea_id'] == $dea_id)
						$dea_alloc['expense'] = $expense;	
				}

				$assigned[] = $dea_alloc;
			}
		}
		
		return $assigned;
	}
	
	function dea_absorption_report($current_month_date=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');
		
		if($current_month_date == "") $current_month_date = date("Y-m-01");
		
		$timeline = "ytd";
		
		if(isset($_POST['timeline'])){
			$timeline = $_POST['timeline'];
		}
		
		$this->db->select_sum('amount');
		$this->db->select(array('allocation.dea_id','dea_code'));
		$this->db->group_by('allocation.dea_id');
		$this->db->join('dea','dea.dea_id=allocation.dea_id');
		$allocations = $this->db->get_where('allocation',
		array('alloc_year'=>date('Y',strtotime($current_month_date))))->result_array();
		
		
		$page_data['timeline'] = $timeline;
		$page_data['records'] = $this->assign_expense_to_date($allocations,$current_month_date,$timeline);
        $page_data['current_month'] = strtotime($current_month_date);
        $page_data['selected_date'] = $current_month_date;
        $page_data['page_name']  = __FUNCTION__;
        $page_data['view_type']  = "budget";
        $page_data['page_title'] = get_phrase('burn_rate_report');
        $this->load->view('backend/index', $page_data);
	}

	function dea_absorption_report_scroll($current_month_date="",$scroll_count=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');
		
		$selected_date = strtotime($scroll_count." months",$current_month_date);
		
		// $this->db->select_sum('amount');
		// $this->db->select(array('allocation.dea_id','dea_code'));
		// $this->db->group_by('allocation.dea_id');
		// $this->db->join('dea','dea.dea_id=allocation.dea_id');
		// $allocations = $this->db->get_where('allocation',
		// array('alloc_year'=>date('Y',$selected_date)))->result_array();
		
		if($current_month_date == "") $current_month_date = date("Y-m-01");
		
		$timeline = "ytd";
// 		
		// if(isset($_POST['timeline'])){
			// $timeline = $_POST['timeline'];
		// }
		
		$this->db->select_sum('amount');
		$this->db->select(array('allocation.dea_id','dea_code'));
		$this->db->group_by('allocation.dea_id');
		$this->db->join('dea','dea.dea_id=allocation.dea_id');
		$allocations = $this->db->get_where('allocation',
		array('alloc_year'=>date('Y',$selected_date)))->result_array();
		
		
		$page_data['timeline'] = $timeline;
		$page_data['records'] = $this->assign_expense_to_date($allocations,date("Y-m-d",$selected_date),$timeline);
        $page_data['current_date'] = date("Y-m-01",$current_month_date);
        $page_data['selected_date'] = date("Y-m-01",$selected_date);
        $page_data['current_month'] = $selected_date;
        $page_data['page_name']  = __FUNCTION__;	
		$page_data['page_title']  = get_phrase('dea_absorption_report');
			
		echo $this->load->view('backend/budget/dea_absorption_report',$page_data,true);		
	}
	
	function overall_budget($start_date=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');
		
		$start_year_date = date('Y-m-01',strtotime('first day of january this year'));
		$end_year_date =  date('Y-m-t',strtotime('last day of december this year'));
		
		if($start_date !== ""){
			$start_year_date = date('Y-m-01',strtotime('first day of january',$start_date));
			$end_year_date = date('Y-m-t',strtotime('last day of december',$start_date));
		}
		
		// $check_restriction = $this->db->get_where('field_restriction',
		// array('restricted_to_object'=>'view_budget','role_id'=>$this->session->role_id))->num_rows();
// 		
		// if($check_restriction > 0){
			// $this->db->where(array('budget.office_code'=>$this->session->office_id));	
		// }
		
		$latest_forcast = $this->get_lasted_year_forecast(date('Y',strtotime($start_year_date)));
		
		$this->db->select(array('forecast_period','budget_section.name','office.name as office_name','month'));
		$this->db->select_sum('amount');
		$this->db->group_by(array('budget.budget_section_id','office.office_code','month'));
		$this->db->join('budget','budget.budget_id=budget_spread.budget_id');
		$this->db->join('budget_section','budget_section.budget_section_id=budget.budget_section_id');
		$this->db->join('office','office.office_id=budget.office_code');
		$this->db->where(array('start_date>='=>$start_year_date,'end_date<='=>$end_year_date,
		"forecast_period"=>$latest_forcast));
		$records = $this->db->get('budget_spread')->result_array();
		
		$budget_grid = array();
		
		
		foreach($records as $row){
			$budget_grid[$row['name']][$row['office_name']][$row['month']] = $row['amount'];
		}
		
		// foreach($records as $row){
			// $budget_grid[$row['name']][$row['office_name']]['total'] = array_sum($budget_grid[$row['name']][$row['office_name']]);
		// }
// 		
		
		$page_data['start_year_date'] = strtotime($start_year_date);
		$page_data['records'] = $budget_grid;
        $page_data['page_name']  = __FUNCTION__;
        $page_data['view_type']  = "budget";
        $page_data['page_title'] = get_phrase(__FUNCTION__);
        $this->load->view('backend/index', $page_data);		
	}
	
	function budget_gap_report($start_date=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');
		
		//$start_date = strtotime($start_date);	
		
		$start_year_date = date('Y-m-01',strtotime('first day of january this year'));
		$end_year_date =  date('Y-m-t',strtotime('last day of december this year'));
		
		if($start_date !== ""){
			$start_year_date = date('Y-m-01',strtotime('first day of january',$start_date));
			$end_year_date = date('Y-m-t',strtotime('last day of december',$start_date));
		}
		
		$alloc_year = date('Y',strtotime($start_year_date));
		
		
		// $check_restriction = $this->db->get_where('field_restriction',
		// array('restricted_to_object'=>'view_budget','role_id'=>$this->session->role_id))->num_rows();
		
		// if($check_restriction > 0){
			// $this->db->where(array('budget.office_code'=>$this->session->office_id));	
		// }
		
		//Budget Per Field Office
		$latest_forecast_year = $this->get_lasted_year_forecast($alloc_year);
		$this->db->select(array('office.name as office','budget_section.short_name as budget_type'));
		$this->db->select_sum('amount');
		$this->db->join('budget','budget.budget_id=budget_spread.budget_id');
		$this->db->join('office','office.office_id=budget.office_code');
		$this->db->join('budget_section','budget_section.budget_section_id = budget.budget_section_id');
		$this->db->where(array('start_date>='=>$start_year_date,'end_date<='=>$end_year_date,
		'forecast_period'=>$latest_forecast_year));
		$this->db->group_by(array('office.name','budget_section.name'));
		$budget = $this->db->get('budget_spread')->result_object();
		
		
		//Allocations per Field Office
		$this->db->select(array('office.name as office','budget_section.short_name as budget_type'));
		$this->db->select_sum('amount');
		$this->db->join('dea','dea.dea_id=allocation.dea_id');
		$this->db->join('office','office.office_id=dea.office_id');
		$this->db->join('budget','budget.budget_id=allocation.budget_id');
		$this->db->join('budget_section','budget_section.budget_section_id = budget.budget_section_id');
		$this->db->group_by(array('office.name','budget_section.name','alloc_year'));
		$allocations = $this->db->get_where('allocation',array('alloc_year'=>$alloc_year,
		'forecast_period'=>$latest_forecast_year))->result_object();
		
		$budget_with_allocation_grid = array();
		
		foreach($budget as $row){
			
			foreach($allocations as $alloc){
				$budget_with_allocation_grid[$row->office]['budget'][$row->budget_type] = $row->amount;
				$budget_with_allocation_grid[$alloc->office]['allocation'][$alloc->budget_type] = $alloc->amount;
			}
		}
		
		
		$page_data['records'] = $budget_with_allocation_grid;
		$page_data['start_year_date'] = strtotime($start_year_date);
        $page_data['page_name']  = __FUNCTION__;
        $page_data['view_type']  = "budget";
        $page_data['page_title'] = get_phrase(__FUNCTION__);
        $this->load->view('backend/index', $page_data);
	}
}
