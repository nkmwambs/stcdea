<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *	@author 	: Nicodemus Karisa
 *	date		: 6th June, 2018
 *	AFR Staff Recognition system
 *	https://www.compassion.com
 *	NKarisa@ke.ci.org
 */

class Account extends CI_Controller
{
    private $extra_callback_parameter = "";

	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		$this->load->model('budget_model');

		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', "account");

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

	public function offices(){
		if ($this->session->userdata('user_login') != 1 ||
			(!in_array('setup_offices', $this->session->privileges) && $this->session->is_super_user != 1)
		)
            redirect(base_url() . 'login', 'refresh');

			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid

		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();	
			
		/** Grid Subject **/
		$crud->set_subject(get_phrase('field_office'));

		/**Select Category Table**/
		$crud->set_table('office');
		
		/**Columns to display**/
		$crud->columns(array("name","office_code"));
		
		/**Fields in add and edit form**/
		$crud->fields(array("name","office_code"));	

		/**Give columns user friendly labels**/
		$crud->display_as('name',get_phrase('field_office'))
			->display_as('lastmodifieddate',get_phrase('last_modified_date'))
			->display_as('createddate',get_phrase('created_date'));

		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}


    public function departments(){
    	if ($this->session->userdata('user_login') != 1 ||
			(!in_array('setup_functions', $this->session->privileges) && $this->session->is_super_user != 1)
		)
            redirect(base_url() . 'login', 'refresh');


			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('function'));

		/**Select Category Table**/
		$crud->set_table('department');
		
		/**Set a relationship**/
		// $crud->set_relation_n_n(get_phrase("positions"), "allocate_department", "role", "department_id", "role_id", "name");
		
		/**Give columns user friendly labels**/
		$crud->display_as('name',get_phrase('function'))
			->display_as('lastmodifieddate',get_phrase('last_modified_date'))
			->display_as('createddate',get_phrase('created_date'));
			
		/**Columns to show**/	
		//$crud->columns(array("name","positions"));
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		

		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase("function");
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}

	// function insert_department($post_array){
		// if($this->db->get_where("department",array("name"=>$post_array["name"]))->num_rows() == 0 ){
			// return $this->db->insert("department",$post_array);
		// }else{
			// return $this->db->get_where("department",array("name"=>$post_array["name"]))->row();
		// }
	// }
// 
	// function delete_department($primary_key){
// 
		// $this->db->join("role","role.role_id=user.role_id");
		// $this->db->join("department","department.department_id=role.department_id");
		// $this->db->where(array("department.department_id"=>$primary_key));
		// $rows = $this->db->get("user")->num_rows();
// 
		// if($rows === 0){
			// $this->db->where(array("department_id"=>$primary_key));
// 
			// return $this->db->delete("department");
		// }else{
// 
			// return FALSE;
		// }
	// }
// 
	// function count_staff_department($value,$row){
		// $this->db->join("role","role.role_id=user.role_id");
		// $this->db->join("department","department.department_id=role.department_id");
		// $this->db->where(array("department.department_id"=>$row->department_id));
		// return $this->db->get("user")->num_rows();
	// }
// 
	// public function insert_department_audit_parameters($post_array,$primary_key){
		// $post_array['created_by'] = $this->session->login_user_id;
		// $post_array['created_date'] = date('Y-m-d h:i:s');
		// $post_array['last_modified_by'] = $this->session->login_user_id;
// 
		// $this->db->where(array("category_id"=>$primary_key));
		// $this->db->update("department",$post_array);
// 
		// return true;
	// }
// 
	// public function update_department_audit_parameters($post_array,$primary_key){
// 
		// $data['last_modified_by'] = $this->session->login_user_id;
// 
		// $this->db->update('department',$data,array('category_id' =>$primary_key));
// 
		// return true;
	// }

	public function profiles(){
		if ($this->session->userdata('user_login') != 1 || 
			(!in_array('setup_user_profiles', $this->session->privileges) && $this->session->is_super_user != 1)
		)
	            redirect(base_url() . 'login', 'refresh');

					/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('profiles'));

		/**Select Category Table**/
		$crud->set_table('profile');
		
		
		/**Set a relationship**/
		$crud->set_relation_n_n(get_phrase("access"), "access", "entitlement", "profile_id", "entitlement_id", 
		"name",'',array('entitlement.derivative_id<>'=>$this->session->system_access_id));
		
		//$crud->set_relation_n_n(get_phrase("access"), "allocate_access", "access", "profile_id", "access_id", "name");
		//$crud->set_relation_n_n(get_phrase("access"), "allocate_access", "access", "profile_id", "access_id", "name");
		
		/**Give columns user friendly labels**/
		$crud->display_as('name',get_phrase('profile'))
			->display_as('lastmodifieddate',get_phrase('last_modified_date'))
			->display_as('createddate',get_phrase('created_date'));
			
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}
	
	
	public function positions(){
	  	if ($this->session->userdata('user_login') != 1 ||
			(!in_array('setup_positions', $this->session->privileges) && $this->session->is_super_user != 1)
		)
            redirect(base_url() . 'login', 'refresh');


			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('positions'));

		/**Select Category Table**/
		$crud->set_table('role');
		
		$crud->display_as(array('department_id'=>get_phrase('department')))
			->display_as(array('name'=>get_phrase('department_name')));
		
		//Relate the department table to the role table
		$crud->set_relation('department_id', 'department', 'name');
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		

		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}
	
		public function staff(){
	  	if ($this->session->userdata('user_login') != 1 ||
			(!in_array('setup_staff', $this->session->privileges) && $this->session->is_super_user != 1)
		)
            redirect(base_url() . 'login', 'refresh');


			/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('staff'));

		/**Select Category Table**/
		$crud->set_table('staff');
		
		/**Relationships**/
		$crud->set_relation("office_id", "office", "name");
		$crud->set_relation("role_id", "role", "name");
		//$crud->set_relation("budget_themes_id", "budget_themes", "name");
		
		/**Columns, Edit and Add forms fields**/	
		$crud->unset_columns(array("lastmodifieddate","createddate"));
		$crud->unset_fields(array("lastmodifieddate","createddate"));
		
		/**Give columns user friendly labels**/
		$crud->display_as('office_id',get_phrase('office'))
			->display_as('role_id',get_phrase('position'));
		
		$crud->columns(array('name','staff_code','office_id','role_id'));
		
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}


  	/** MANEGE USER INFORMATION **/

	public function manage_users(){
		if ($this->session->userdata('user_login') != 1 || 
		(!in_array(__FUNCTION__, $this->session->privileges) && $this->session->is_super_user != 1)
		)
	            redirect(base_url() . 'login', 'refresh');

					/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('users'));

		/**Select Category Table**/
		$crud->set_table('user');
		
		/**Show Columns**/
		$crud->columns(array("name","gender","email",'office_id',"role_id","profile_id",'is_super_user',"auth"));
		
		/** Set Relationship fields**/
		//$crud->set_relation("office_id", "office", "name");
		// $crud->set_relation("role_id", "role", "name");
		$crud->set_relation("profile_id", "profile", "name");
		$crud->set_relation('office_id','office','name');
		$crud->set_relation('role_id','role','name');
		
		/**Set table codes**/
		$crud->field_type("auth", "dropdown",array("0"=>"suspended","1"=>"active"));
		$crud->field_type("is_super_user", "dropdown",array("0"=>"No","1"=>"Yes"));
		
		/** Readable Label **/
		$crud->display_as("name",get_phrase("user_name"))
			->display_as("profile_id",get_phrase("user_profile"))
			->display_as("office_id",get_phrase("office"))
			->display_as("role_id",get_phrase("role"))
			->display_as("auth",get_phrase("status"))
			->display_as('lastmodifieddate',get_phrase('last_modified_date'))
			->display_as('createddate',get_phrase('created_date'));
		
		/** Add form fields **/	
		$crud->add_fields(array("name","gender","email","office_id","role_id","profile_id",'is_super_user'));
		
		
		$crud->field_type('gender','dropdown',array('male'=>'Male','female'=>'Female'));
		
		/** Edit form fields **/
		$crud->edit_fields(array("name","gender","email","office_id","role_id","profile_id","auth",'is_super_user'));		
		
		/**Call backs**/
		$crud->callback_after_insert(array($this,'send_registration_email_notification'));
		
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}		
	
	public function send_registration_email_notification($post_array,$primary_key){
	 	$this->load->model('Email_model');
		
	 	$password = substr(md5(rand(10000, 50000000)),0,8);
		
	 	/**Send Email**/
	 	$this->email_model->account_opening_email($post_array['email'],$password);
		
		$data['password'] = md5($password);
		
		$this->db->where(array('user_id'=>$primary_key));
		
		$this->db->update('user',$data);
 
   	 	return true;
	}
		
	public function reset_user_password(){
		
	}

	public function mail_templates(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('templates'));

		/**Select Category Table**/
		$crud->set_table('template');

		$crud->callback_edit_field('mail_tags', array($this,"mail_tags_readonly"));	//template_name_readonly
		$crud->callback_edit_field('name', array($this,"template_name_readonly"));
		$crud->callback_edit_field('template_trigger', array($this,"template_trigger_readonly"));

		/**Callbacks**/
		$crud->callback_after_insert(array($this,'insert_survey_audit_parameters'));
		$crud->callback_after_update(array($this,'update_survey_audit_parameters'));

		/** Assign Privileges **/
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"add_survey")) $crud->unset_add();
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"edit_survey")) $crud->unset_edit();
		$crud->unset_delete();


		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}

	function mail_tags_readonly($value, $primary_key) {
			$tags_array = explode(",", $value);
			$tag_str = "";
			foreach($tags_array as $tag){
				$tag_str .= "<div class='label label-primary'>".$tag."</div>&nbsp;";
			}
			return $tag_str;
	}

	function template_name_readonly($value, $primary_key) {
			return '<span>'.$value.'</span>';
	}

	function template_trigger_readonly($value, $primary_key) {
			return '<span>'.$value.'</span>';
	}

	/** AJAX LOADED CONTENT END**/
	
	function account_themes(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('budget_theme'));

		/**Select Category Table**/
		$crud->set_table('budget_themes');
		
		/** Relate to Budget themes **/
		//$crud->set_relation('budget_themes_id', 'budget_themes', 'name');
		
		/**Fields to show**/
			
		$crud->columns(array('name'));
		$crud->fields(array('name'));
			
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);	
	}
	
	function account_groups(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('account_group'));

		/**Select Category Table**/
		$crud->set_table('budget_account_group');
		
		/** Relate to Budget themes **/
		//$crud->set_relation('budget_themes_id', 'budget_themes', 'name');
		
		/**Fields to show**/
			
		$crud->columns(array('name'));
		$crud->fields(array('name'));
		
		/** User friendly field labels **/
		//$crud->display_as(array('budget_themes_id'=>get_phrase('budget_theme')));
			
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}

	function budget_account(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid
		
		$crud->unset_bootstrap();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		/** Grid Subject **/
		$crud->set_subject(get_phrase('account'));

		/**Select Category Table**/
		$crud->set_table('budget_account');
		
		/** Relate to Budget themes **/
		//$crud->set_relation('budget_account_group_id', 'budget_account_group', 'name');
		
		/**Fields to show**/
			
		$crud->columns(array('name','budget_account_code'));
		$crud->fields(array('name','budget_account_code'));
		
		/** User friendly field labels **/
		//$crud->display_as(array('budget_account_group_id'=>get_phrase('account_group')));
		
		/**Required fields**/
		$crud->required_fields(array('name','budget_account_code'));
			
		$output = $crud->render();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);		
	}

	function restriction(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		$results = array();
		$raw_results = $this->stcdea_model->user_restriction_objects('restriction_object_name');
		
		foreach($raw_results as $object=>$values){
			$results[$object] = group_array_by_key($values,'name',array('user_id'));
		}
		
		$page_data['results'] = $results;
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
        $this->load->view('backend/index', $page_data);
	}
	
	function list_restriction_values($object){
		
		$object_name = $this->db->get_where('restriction_object',
		array('restriction_object_id'=>$object))->row()->restriction_object_name;
		//$load_data['object'] = $object_name;
		$results = $this->db->get($object_name)->result_object();
		//$this->load->view('backend/account/ajax_list_restriction_values/',$load_data,true);
		$string = "";
		
		foreach($results as $result){
			$id = $object_name."_id";
			$string .="<option value='".$result->$id."'>".$result->name."</option>";
		}
		
		echo $string;
		
	}
	
	function restricted_users($object){
		
		$object_name = $this->db->get_where('restriction_object',
		array('restriction_object_id'=>$object))->row()->restriction_object_name;

		$results_query = "SELECT * FROM user WHERE user_id NOT IN (SELECT user_id FROM user_restriction WHERE restriction_object_id = ".$object.")"; 	
		
		$results = $this->db->query($results_query)->result_object();
			
		$string = "";
		
		foreach($results as $result){
			$id = $object_name."_id";
			$string .="<option value='".$result->$id."'>".$result->name."</option>";
		}
		
		echo $string;
		
	}	
	
	function add_user_restriction(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		
		 
		$this->db->select(array('user_id','name'));
		$page_data['users'] = $this->db->get('user')->result_object();
		$page_data['objects'] = $this->db->get('restriction_object')->result_object();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
        $this->load->view('backend/index', $page_data);
	}

	function upload_setup($param1=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		$page_data['upload_type'] = $param1;
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$this->load->view('backend/index', $page_data);
	}
	
	function review_setup_uploaded($param1="",$param2=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');

  		if ($param1 == 'import_excel')
  		{			
  			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/excel/'.$param2.'_template.xlsx');
  			
  			include 'Simplexlsx.class.php';
			//include APPPATH.'controllers/Simplexlsx.class.php';

  			$xlsx = new SimpleXLSX('uploads/excel/'.$param2.'_template.xlsx');

  			//list($num_cols, $num_rows) = $xlsx->dimension();
			
  		}
		
		$page_data['upload_type'] = $param2;
		$page_data['uploaded_data'] = $xlsx->rows();
		$page_data['view_type']  = "account";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase('upload_'.$param2);
		$this->load->view('backend/index', $page_data);
	}
	
	function upload_reviewed_setup_data($param1=""){
		
		$post_array = $this->input->post();
		
		$fields = array_keys($post_array);	
		
		$rows = array();
		
		for($i=0;$i<sizeof($post_array[$fields[0]]);$i++){
			
			foreach($fields as $field){
				
				$value = $post_array[$field][$i];
				
				if(substr_count($field, 'related_code')){
						
					$table_arr = explode("_", $field);
					
					$table = $table_arr[0];
					
					$str = $table."_id";
					
					$field_code = $table."_code";
					
					if($this->db->get_where($table,array($field_code=>$post_array[$field][$i]))->num_rows() > 0){
						$rows[$i][$str] = $this->db->
						get_where($table,array($field_code=>$post_array[$field][$i]))->row()->$str;	
					}	
					
				}else{
					$rows[$i][$field] = $value;
				}
				
			}
		}
		
		$inserted_rows = 0;
		
		foreach($rows as $row){
			
			$this->db->insert($param1,$row);
			
			if($this->db->affected_rows() > 0) {
				$inserted_rows++;
			}
			
		}
		
		if($inserted_rows > 0 ){
			echo $inserted_rows.' '.get_phrase('inserted');
		}else{
			echo get_phrase('no_recorded_affected');
		}
		
		//echo json_encode($rows);
	}
}
