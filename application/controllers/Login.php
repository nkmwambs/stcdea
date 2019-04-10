<<<<<<< HEAD
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

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		//$this->db->cache_on();
		//$this->output->cache(60);
		$this->db->cache_delete_all();
    }

    //Default function, redirects to logged in user area
    public function index() {

        if ($this->session->userdata('user_login') == 1)  
        	redirect(base_url() . 'dashboard', 'refresh');

        $this->load->view('backend/login');
    }

    //Ajax login function 
    function ajax_login() {
        $response = array();

        //Recieving post input of email, password from ajax request
        $email = $_POST["email"];
        $password = $_POST["password"];
        $response['submitted_data'] = $_POST;

        //Validating login
        $login_status = $this->validate_login($email, $password);
        $response['login_status'] = $login_status;
        if ($login_status == 'success') {
            $response['redirect_url'] = '';
        }

        //Replying ajax request with validation response
        echo json_encode($response);
    }

    //Validating login from ajax request
    function validate_login($email = '', $password = '') {
        $credential = array('email' => $email,"auth"=>1,"password"=>md5($password));


        // Checking login credential for admin
        $query = $this->db->get_where('user', $credential);
        if ($query->num_rows() > 0) {
			$row =  $query->row();
		    $this->session->set_userdata('user_login', '1');
		    $this->session->set_userdata('login_user_id', $row->user_id);
		    $this->session->set_userdata('name', $row->name);
		    $this->session->set_userdata('profile_id', $row->profile_id);
			$this->session->set_userdata('is_super_user', $row->is_super_user);	
			$this->session->set_userdata('role_id', $row->role_id);
			$this->session->set_userdata('office_id', $row->office_id);
			$this->session->set_userdata('system_access_id',$this->db->get_where('entitlement',
			array('name'=>'system'))->row()->entitlement_id);
			
			$this->db->join('entitlement','entitlement.entitlement_id=access.entitlement_id');
			$this->db->select(array('entitlement.name as privilege'));
			$access_obj = $this->db->get_where('access',array('profile_id'=>$row->profile_id));
			
			//Set up the privilege array to be used to check if the privilege exists for a logged user
			$privileges = array();
			
			if($access_obj->num_rows() > 0){
				$privileges = array_column($access_obj->result_array(),'privilege');
			}
			
			$this->session->set_userdata('privileges', $privileges);	
			$this->session->set_userdata('profile_id', $row->profile_id);	
			
			//Flag the user as a logged in user in the database
			$this->db->where(array('user_id'=>$row->user_id));
			$this->db->update('user',array('online'=>1));
						
            return 'success';
        }

       

        return 'invalid';
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    // PASSWORD RESET BY EMAIL
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }

    function ajax_forgot_password()
    {
        $resp                   = array();
        $resp['status']         = 'false';
        $email                  = $_POST["email"];
        //$reset_account_type     = '';
        //resetting user password here
        $new_password           =   substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for user
        $query = $this->db->get_where('user' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $this->db->where('email' , $email);
            $this->db->update('user' , array('password' => md5($new_password)));
            $resp['status']         = 'true';
        }
       

        //send new password to user email  
        $this->email_model->password_reset_email($new_password , $email);

        $resp['submitted_data'] = $_POST;

        echo json_encode($resp);
    }

    /*     * *****LOGOUT FUNCTION ****** */

    function logout() {
    	//Update the user table online field to 0 for logged out user
    	$this->db->where(array('user_id'=>$this->session->login_user_id));
    	$this->db->update('user',array('online'=>0));
    	
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

}
=======
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

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		//$this->db->cache_on();
		//$this->output->cache(60);
		$this->db->cache_delete_all();
    }

    //Default function, redirects to logged in user area
    public function index() {

        if ($this->session->userdata('user_login') == 1)  
        	redirect(base_url() . 'dashboard', 'refresh');

        $this->load->view('backend/login');
    }

    //Ajax login function 
    function ajax_login() {
        $response = array();

        //Recieving post input of email, password from ajax request
        $email = $_POST["email"];
        $password = $_POST["password"];
        $response['submitted_data'] = $_POST;

        //Validating login
        $login_status = $this->validate_login($email, $password);
        $response['login_status'] = $login_status;
        if ($login_status == 'success') {
            $response['redirect_url'] = '';
        }

        //Replying ajax request with validation response
        echo json_encode($response);
    }

    //Validating login from ajax request
    function validate_login($email = '', $password = '') {
        $credential = array('email' => $email,"auth"=>1,"password"=>md5($password));


        // Checking login credential for admin
        $query = $this->db->get_where('user', $credential);
        if ($query->num_rows() > 0) {
			$row =  $query->row();
		    $this->session->set_userdata('user_login', '1');
		    $this->session->set_userdata('login_user_id', $row->user_id);
		    $this->session->set_userdata('name', $row->name);
		    $this->session->set_userdata('profile_id', $row->profile_id);
			$this->session->set_userdata('is_super_user', $row->is_super_user);	
			$this->session->set_userdata('role_id', $row->role_id);
			$this->session->set_userdata('office_id', $row->office_id);
			$this->session->set_userdata('system_access_id',$this->db->get_where('entitlement',
			array('name'=>'system'))->row()->entitlement_id);
			
			$this->db->join('entitlement','entitlement.entitlement_id=access.entitlement_id');
			$this->db->select(array('entitlement.name as privilege'));
			$access_obj = $this->db->get_where('access',array('profile_id'=>$row->profile_id));
			
			//Set up the privilege array to be used to check if the privilege exists for a logged user
			$privileges = array();
			
			if($access_obj->num_rows() > 0){
				$privileges = array_column($access_obj->result_array(),'privilege');
			}
			
			$this->session->set_userdata('privileges', $privileges);	
			$this->session->set_userdata('profile_id', $row->profile_id);	
			
			//Flag the user as a logged in user in the database
			$this->db->where(array('user_id'=>$row->user_id));
			$this->db->update('user',array('online'=>1));
						
            return 'success';
        }

       

        return 'invalid';
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    // PASSWORD RESET BY EMAIL
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }

    function ajax_forgot_password()
    {
        $resp                   = array();
        $resp['status']         = 'false';
        $email                  = $_POST["email"];
        //$reset_account_type     = '';
        //resetting user password here
        $new_password           =   substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for user
        $query = $this->db->get_where('user' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $this->db->where('email' , $email);
            $this->db->update('user' , array('password' => md5($new_password)));
            $resp['status']         = 'true';
        }
       

        //send new password to user email  
        $this->email_model->password_reset_email($new_password , $email);

        $resp['submitted_data'] = $_POST;

        echo json_encode($resp);
    }

    /*     * *****LOGOUT FUNCTION ****** */

    function logout() {
    	//Update the user table online field to 0 for logged out user
    	$this->db->where(array('user_id'=>$this->session->login_user_id));
    	$this->db->update('user',array('online'=>0));
    	
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

}
>>>>>>> 9e88b3b8f4be2c3aeccaabf46f10ce3dce528500
