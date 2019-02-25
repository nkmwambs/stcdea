<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Compasion International
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Africa Dev Team 
 * @copyright	Copyright (c) 2018 - 2019, Compassion.
 * @license		https://www.compassion-africa.org/user_guide/license.html
 * @link		https://www.compassion-africa.org
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('get_access'))
{
	function get_access($child_class,$parent_class='system',$visible = 1) {
		$CI	=&	get_instance();
		$CI->load->database();
		
		$parent_obj	=	$CI->db->get_where('entitlement' , array('name' => $parent_class));
		$child_obj	=	$CI->db->get_where('entitlement' , array('name' => $child_class));
		
		//Check if the phrase is available in the database: Insert if false
		
		if($parent_obj->num_rows() == 0) 
			$CI->db->insert('entitlement',array('name'=>$parent_class,"derivative_id"=>0));
		
		if($child_obj->num_rows() == 0) {
			
			$parent_class_id = $CI->db->get_where('entitlement',array('name'=>$parent_class))->
			row()->entitlement_id;
			
			$CI->db->insert('entitlement',array('name'=>$child_class,"derivative_id"=>$parent_class_id,'visibility'=>$visible));
				
		}else{
			
			//Update the Parent Identifier if changed
			
			$parent_class_id = $CI->db->get_where('entitlement',array('name'=>$parent_class))->
			row()->entitlement_id;
			
			$check_change_of_parent_obj = $CI->db->get_where('entitlement' , array('name' => $child_class,
			'derivative_id'=>$parent_class_id));
			
			$check_change_of_visibility_obj = $CI->db->get_where('entitlement' , array('name' => $child_class,
			'visibility'=>$visible));
			
			if($check_change_of_parent_obj->num_rows() == 0 || $check_change_of_visibility_obj->num_rows() == 0){
				
				$CI->db->where(array("name"=>$child_class));
				$CI->db->update('entitlement',array('derivative_id'=>$parent_class_id,'visibility'=>$visible));
			}
		}
		
		//Check if visibility changed
		
		return $child_class;
	}
}

// ------------------------------------------------------------------------
/* End of file access_profiling_helper.php */
/* Location: ./application/helpers/access_profiling_helper.php */