<?php

class Stcdea_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
		
	function get_table_results($table){

		$results = $this->db->get($table)->result_object();
		
		return $results;
	}
}
