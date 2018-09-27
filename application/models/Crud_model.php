<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
        //return $this->db->get_where($type, array($type . '_id' => $type_id))->row()->$field;
        if($this->db->get_where($type, array($type . '_id' => $type_id))->num_rows() > 0 ){
        	return $this->db->get_where($type, array($type . '_id' => $type_id))->row()->$field;
        }else{
        	return NULL;
        }
    }
	
	
	function get_field_value($type, $search_field = '', $search_value = 'name',$show_field) {
        //return $this->db->get_where($type, array($type . '_id' => $type_id))->row()->$field;
        if($this->db->get_where($type, array($search_field => $search_value))->num_rows() > 0 ){
        	return $this->db->get_where($type, array($search_field => $search_value))->row()->$show_field;
        }else{
        	return NULL;
        }
    }
	
	
	public function get_results_by_id($type, $type_id = ''){
		$result = $this->db->get($type)->result_object();
		
		if($type_id!==""){
			$result = $this->db->get_where($type,array( $type . "_id"=>$type_id))->row();
		}
		
		return $result;
	}
	
	public function get_results_by_related_id($type, $id_field,$id,$all=false){
	
		$result = $this->db->get_where($type,array( $id_field=>$id))->row();
		if($all === true){
			$result = $this->db->get_where($type,array($id_field=>$id))->result_object();	
		}
		return $result;
	}
	
	
	
	    
    ////////BACKUP RESTORE/////////
    function create_backup($type) {
        $this->load->dbutil();


        $options = array(
            'format' => 'txt', // gzip, zip, txt
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );


        if ($type == 'all') {
            $tables = array('');
            $file_name = 'system_backup';
        } else {
            $tables = array('tables' => array($type));
            $file_name = 'backup_' . $type;
        }

        $backup = & $this->dbutil->backup(array_merge($options, $tables));


        $this->load->helper('download');
        force_download($file_name . '.sql', $backup);
    }
	
	


    /////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
    function restore_backup() {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
        $this->load->dbutil();


        $prefs = array(
            'filepath' => 'uploads/backup.sql',
            'delete_after_upload' => TRUE,
            'delimiter' => ';'
        );
        $restore = & $this->dbutil->restore($prefs);
        unlink($prefs['filepath']);
    }

    /////////DELETE DATA FROM TABLES///////////////
    function truncate($type) {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    ////////IMAGE URL//////////
    function get_image_url($id = '') {
        if (file_exists('uploads/user_image/' . $id . '.jpg'))
            $image_url = base_url() . 'uploads/user_image/' . $id . '.jpg';
        else
            $image_url = base_url() . 'uploads/user.jpg';

        return $image_url;
    }

       ////////private message//////
    function send_new_private_message() {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $reciever   = $this->input->post('reciever');
        $sender     = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->num_rows();

        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['reciever']            = $reciever;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->row()->message_thread_code;
        if ($num2 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->row()->message_thread_code;


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }
	
	/**USER PREVILEDGES**/
	
	function user_privilege($param1="",$privilege=""){
		
		$user_previledges = array();
		$arr = $this->db->join("entitlement","entitlement.entitlement_id=access.entitlement_id")->get_where('access',array("profile_id"=>$param1))->result_object();
			
		foreach($arr as $row){
			$user_previledges[] = $row->name;
		}
		
		return in_array($privilege, $user_previledges) ? true : false; 

	}
}
