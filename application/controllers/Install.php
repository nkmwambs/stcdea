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

class Install extends CI_Controller
{
    
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        $this->load->view('backend/install');
    }
    
    
    
}
