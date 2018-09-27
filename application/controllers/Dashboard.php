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

class Dashboard extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');

		/** System Feature Session Tag **/
		$this->session->set_userdata('view_type', "dashboard");

       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

    }

    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = "dashboard";
        $page_data['view_type']  = "dashboard";
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }

    /***DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = __FUNCTION__;
        $page_data['view_type']  = "dashboard";
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }

	
}
