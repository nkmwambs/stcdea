<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }

	function account_opening_email($account_type = '' , $email = '')
	{
		$system_name	=	$this->db->get_where('settings' , array('type' => 'system_name'))->row()->description;
		$query			=	$this->db->get_where(users , array('email' => $email));	
		
		$email_msg		=	"Dear ".$query->row()->firstname.",<br />";	
		$email_msg		.=	"Welcome to ".$system_name."<br />";
		$email_msg		.=	"Your account type : ".$account_type."<br />";
		$email_msg		.=	"Your login password : ".$this->db->get_where("user" , array('email' => $email))->row()->password."<br />";
		$email_msg		.=	"Login Here : ".base_url()."<br />";
		
		$email_sub		=	"Account opening email";
		$email_to		=	$email;
		
		$this->do_email($email_msg , $email_sub , $email_to);
	}
	
	function password_reset_email($new_password = '' , $email = '')
	{
		$query			=	$this->db->get_where(users , array('email' => $email));
		if($query->num_rows() > 0)
		{
			
			$email_msg	=	"Dear ".$query->row()->firstname.",<br />";
			$email_msg	.=	"Your password is : ".$new_password."<br />";
			
			$email_sub	=	"Password reset request";
			$email_to	=	$email;
			$this->do_email($email_msg , $email_sub , $email_to);
			return true;
		}
		else
		{	
			return false;
		}
	}
	
	/*** Mail Templates  ***/
	
	function manage_account_email($user_id="",$template_trigger="",$password=""){
		$template = $this->db->get_where("template",array("template_trigger"=>$template_trigger));
		
		$template_subject = $template->row()->template_subject;
		$template_body = $template->row()->template_body;
		
		$user = $this->db->get_where("user",array("user_id"=>$user_id))->row();
		
		$tags['{user}'] = $user->firstname." ".$user->lastname;
		$tags['{system_name}'] = $this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
		$tags['{user_email}'] = $user->email;
		$tags['{user_password}'] = $password;
		$tags['{user_role}'] = $this->db->get_where("role",array("role_id"=>$user->role_id))->row()->name;
		$tags['{user_profile}'] = $this->db->get_where("profile",array("profile_id"=>$user->profile_id))->row()->name;
		$tags['{site_url}'] = base_url();
		$tags['{system_admin_email}'] = $this->db->get_where('settings' , array('type'=>'system_email'))->row()->description;
		
		/**
		 * 	$a = array( 'truck', 'vehicle', 'seddan', 'coupe' );
		 *	$str = 'Honda is a truck. Toyota is a vehicle. Nissan is a sedan. Scion is a coupe.';
		 *	echo str_replace($a,'car',str_replace('Lexus','Toyota',$str));
		 */
			
		$tag_keys = array_keys($tags);
		$tag_values = array_values($tags);
		$email_msg = str_replace($tag_keys,$tag_values,$template_body);
		
		$email_sub = str_replace($tag_keys,$tag_values,$template_subject);
		
		$email_to	=	$user->email;
		
		
		$this->do_email($email_msg , $email_sub , $email_to);

 	}
	
	
	/***custom email sender****/
	function do_email($msg=NULL, $sub=NULL, $to=NULL, $from=NULL)
	{
		
		$config = array();
        $config['useragent']	= "CodeIgniter";
        $config['mailpath']		= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']		= "smtp";
        $config['smtp_host']	= "localhost";
        $config['smtp_port']	= "25";
        $config['mailtype']		= 'html';
        $config['charset']		= 'utf-8';
        $config['newline']		= "\r\n";
        $config['wordwrap']		= TRUE;

        $this->load->library('email');

        $this->email->initialize($config);

		$system_name	=	$this->db->get_where('settings' , array('type' => 'system_name'))->row()->description;
		if($from == NULL)
			$from		=	$this->db->get_where('settings' , array('type' => 'system_email'))->row()->description;
		
		$this->email->from($from, $system_name);
		$this->email->from($from, $system_name);
		$this->email->to($to);
		$this->email->subject($sub);
		
		$msg	=	$msg."<br /><br /><br /><br /><br /><br /><br /><hr /><center><a href=\"https://www.compassion-africa.org\">&copy; 2018 ".get_phrase("AFR_staff_recognition_system")."</a></center>";
		$this->email->message($msg);
		
		$this->email->send();
		
		//echo $this->email->print_debugger();
	}
}

