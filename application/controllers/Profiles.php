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

class Profiles extends CI_Controller
{
    private $extra_callback_parameter = "";

	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', "profiles");

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
	
	
	function nomination_profiles(){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');


		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('nomination_profile'));

		/**Select Category Table**/
		$crud->set_table('nomination_profile');
		
		/** Assign Privileges **/
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"view_all_nomination_profiles")) $crud->where('user_id',$this->session->login_user_id);
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"show_only_open_profiles")) $crud->where('open',1);
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"add_nomination_profile")) $crud->unset_add();
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"edit_nomination_profile")) $crud->unset_edit();
		if(!$this->crud_model->check_profile_privilege($this->session->profile_id,"delete_nomination_profile")) $crud->unset_delete();
 		
		//** Set File Upload Fields **/
		$crud->set_field_upload('photo_1','uploads/document');
		$crud->set_field_upload('photo_2','uploads/document');
		$crud->set_field_upload('photo_3','uploads/document');
		
		/** Columns to be viewed by user **/
		$crud->columns(array("category_id","beneficiaries_impacted","caregivers_impacted","start_date","active_initiative","photo_1","open"));
		$crud->add_fields(array("category_id","beneficiaries_impacted","caregivers_impacted","start_date","active_initiative","description","photo_1","photo_2","photo_3","video_url"));
		$crud->edit_fields(array("user_id", "category_id","beneficiaries_impacted","caregivers_impacted","start_date","active_initiative","description","photo_1","photo_2","photo_3","video_url"));
		
		// /** Required Fields **/	
		$crud->required_fields(array("category_id","beneficaries_impacted","caregivers_impacted","start_date","active_initiative","description","photo_1"));
		
		/** Callback **/
		$crud->callback_after_insert(array($this, 'update_user_id_after_insert'));
		//$crud->callback_before_insert(array($this,'check_duplicate_profile_for_category'));
		//$crud->callback_add_field('user_id', function () {
			//return '<input type="text" value="'.$this->session->login_user_id.'" name="user_id">';
		//});
		
		//$crud->field_type('user_id','readonly');
		
		// /** Set Field Types **/
		$crud->field_type('active_initiative','dropdown',array('1' => 'yes', '2' => 'no'));
		$crud->field_type('open','dropdown',array('1' => 'yes', '2' => 'no'));
		
		/** Fields Relationship **/
		$crud->set_relation('category_id','category','name');
		
		// /** Change Label Display **/
		 $crud->display_as('user_id',get_phrase("partner_profile_code"))
			 	->display_as('category_id',get_phrase('nomination_category'))
			 	->display_as('start_date',get_phrase('innovation_start_date'))
				->display_as('active_initiative',get_phrase('is_the_initiative_currently_active?'))
				->display_as('video_url',get_phrase('video_link'))
				->display_as('open',get_phrase('open_for_nomination?'));
			
		
		$output = $crud->render();
		$page_data['view_type']  = "profiles";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}

	function check_duplicate_profile_for_category($post_array){
		$this->db->where(array("category_id"=>$post_array['category_id']));
		$profile_exists = $this->db->get("nomination_profile");
		if($profile_exists->num_rows() > 0){
			return FALSE;
		}
		
	}
	
	function update_user_id_after_insert($post_array,$primary_key) {
		
		$data['user_id'] = $this->session->login_user_id;
		$data['open'] = 1;
		
		$this->db->where(array("nomination_profile_id"=>$primary_key));
				 
		return $this->db->update('nomination_profile',$data);
	} 	

	public function profile_summary($param1="",$param2="",$param3=""){
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');


		/**Instatiate CRUD**/
		$crud = new grocery_CRUD();

		/**Set theme to flexigrid**/
		$crud->set_theme('flexigrid');//flexigrid


		/** Grid Subject **/
		$crud->set_subject(get_phrase('summary'));

		$crud->set_model('summary_model');
		$crud->set_table('nomination_profile'); //Change to your table name
		$crud->basic_model->set_query_str('SELECT nomination_profile_id,firstname,nomination_profile.user_id user_id,category.name name, COUNT(*) c FROM nomination_profile JOIN user ON user.user_id=nomination_profile.user_id JOIN category ON category.category_id=nomination_profile.category_id  GROUP BY nomination_profile.user_id,nomination_profile.category_id  HAVING c > 0 ORDER BY firstname'); //Query text here
				

		$output = $crud->render();
		$page_data['view_type']  = "profiles";
		$page_data['page_name']  = __FUNCTION__;
        $page_data['page_title'] = get_phrase(__FUNCTION__);
		$output = array_merge($page_data,(array)$output);
        $this->load->view('backend/index', $output);
	}

}	