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

class Messages extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		
		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', get_called_class());
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
	
	/* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
    	if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'login', 'refresh');
		
			
        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'messages/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'messages/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
		$page_data['view_type']  = "messages";
        $page_data['page_title']                = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
	
}
