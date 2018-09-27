<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author 	: Nicodemus Karisa Mwambire
 *	date		: 29 May, 2018
 *	AFR Staff Recognition System
 *	https://www.compassionkenya.com
 *	support@compassionkenya.com
 */

class Settings extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		
		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', "settings");
		
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
	
	/**System Settings**/
	function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
        
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('text_align');
            $this->db->where('type' , 'text_align');
            $this->db->update('settings' , $data);
			
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'settings/system_settings/', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'settings/system_settings/', 'refresh');
        }
        if ($param1 == 'change_skin') {
            $data['description'] = $param2;
            $this->db->where('type' , 'skin_colour');
            $this->db->update('settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('theme_selected')); 
            redirect(base_url() . 'settings/system_settings/', 'refresh'); 
        }
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
		$page_data['view_type']  = "settings";
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }



/*****SMS SETTINGS*********/
    function sms_settings($param1 = '' , $param2 = '')
    {
		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
        if ($param1 == 'clickatell') {

            $data['description'] = $this->input->post('clickatell_user');
            $this->db->where('type' , 'clickatell_user');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_api_id');
            $this->db->where('type' , 'clickatell_api_id');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'settings/sms_settings/', 'refresh');
        }

        if ($param1 == 'twilio') {

            $data['description'] = $this->input->post('twilio_account_sid');
            $this->db->where('type' , 'twilio_account_sid');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_auth_token');
            $this->db->where('type' , 'twilio_auth_token');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_sender_phone_number');
            $this->db->where('type' , 'twilio_sender_phone_number');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'settings/sms_settings/', 'refresh');
        }

        if ($param1 == 'active_service') {

            $data['description'] = $this->input->post('active_sms_service');
            $this->db->where('type' , 'active_sms_service');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'settings/sms_settings/', 'refresh');
        }

        $page_data['page_name']  = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
		$page_data['view_type']  = "settings";
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

   /*****LANGUAGE SETTINGS*********/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
   		if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		if ($param1 == 'update_phrase') {
			$language	=	$param2;
			$total_phrase	=	$this->input->post('total_phrase');
			for($i = 1 ; $i < $total_phrase ; $i++)
			{
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id' , $i);
				$this->db->update('language' , array($language => $this->input->post('phrase'.$i)));
			}
			redirect(base_url() . 'settings/manage_language/edit_phrase/'.$language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language        = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'settings/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'settings/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);
			
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'settings/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			
			redirect(base_url() . 'settings/manage_language/', 'refresh');
		}
		$page_data['page_name']        = 'manage_language';
		$page_data['page_title']       = get_phrase('manage_language');
		$page_data['view_type']  = "settings";
		//$page_data['language_phrases'] = $this->db->get('language')->result_array();
		$this->load->view('backend/index', $page_data);	
    }
}
