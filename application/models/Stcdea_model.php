<?php

class Stcdea_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
		
	function get_table_results($table){

		$results = $this->db->get($table)->result_object();
		
		return $results;
	}
	
	function user_restriction_objects($group_by = "user_id",$user_id = ""){
		
		//Group By Options: user_id, restriction_object_name, restriction_value_id
		
		$this->db->select(array('restriction_object_name','user.name','user_restriction.user_id',
		'restriction_value_id'));
		
		if($user_id!==""){
			$this->db->where(array('user_restriction.user_id'=>$user_id));
		}
		
		$this->db->join('user_restriction_value',
		'user_restriction_value.user_restriction_id=user_restriction.user_restriction_id');
		$this->db->join('user','user.user_id=user_restriction.user_id');
		$this->db->join('restriction_object',
		'restriction_object.restriction_object_id=user_restriction.restriction_object_id');
		$results = $this->db->get('user_restriction')->result_array();
			
		return group_array_by_key($results,$group_by);
	}
	
	// function get_restricted_objects_where_by_user($user_id,$object){
// 		
		// //Object options: office,sof
// 		
		// $user_restriction = $this->user_restriction_objects('restriction_object_name',$user_id);
// 		
		// $objects_array = "";
// 		
		// if($object == 'office') $this->db->where(array('status'=>1));
// 		
		// if(array_key_exists($object, $user_restriction)){
			// $objects_array = array_column($user_restriction[$object], 'restriction_value_id');
			// $this->db->where_in($object.'.'.$object.'_id',$objects);
		// }
	// }

	function get_restricted_objects($user_id,$object){
		
		//Object options: office,sof
		
		$user_restriction = $this->user_restriction_objects('restriction_object_name',$user_id);
		
		$objects_array = array();
		
		if(array_key_exists($object, $user_restriction)){
			$objects_array = array_column($user_restriction[$object], 'restriction_value_id');
		}
		
		return $objects_array;
	}
}
