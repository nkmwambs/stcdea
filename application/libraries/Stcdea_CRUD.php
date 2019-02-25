<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stcdea_CRUD{
	
	private $CI;
	private $table;
	private $fields;
	private $results;
	private $basic_model = null;
	
	function __construct(){
		$this->CI=& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('Stcdea_model'); 
		$this->basic_model =  new Stcdea_model();
	}
	
	public function set_table($table_name){
		$this->table = $table_name;
		return $this;
	}
	
	public function set_columns(){
		$this->fields = $this->CI->db->list_fields($this->table);
		return $this;
	}
	
	
	private function table_results(){
		return $this->basic_model->get_table_results($this->table);
	}
	
	private function set_view($view_name,$data){
		extract($data);
		
		ob_start();
		
		if(file_exists("assets/stcdea/views/".$view_name.".php")){
			include "assets/stcdea/views/".$view_name.".php";
		}else{ 
			include "assets/stcdea/views/error.php";
		}
		
		$buffered_view = ob_get_contents();
		
		ob_end_clean();
		
		return $buffered_view;
		
	}
	
	
	function render(){
		$data['table_fields'] 		= $this->fields;
		$data['table_results'] 		= $this->table_results();
		
		$table_view = $this->set_view('display_table',$data);

		
		return $table_view;
	}
}
