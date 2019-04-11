<?php

if ( ! function_exists('group_array_by_key'))
{	
	function group_array_by_key($multi_dimensional_array,$key,$fields_to_unset = array()){
		
		$grouped_multi_demensional_array = array();
		
		$loop = 0;
		
		foreach($multi_dimensional_array as $row){
			$grouped_multi_demensional_array[$row[$key]][$loop] = $row; 
			unset($grouped_multi_demensional_array[$row[$key]][$loop][$key]);
			
			foreach($fields_to_unset as $unset_field){
				if(array_key_exists($unset_field, $grouped_multi_demensional_array[$row[$key]][$loop])){
					unset($grouped_multi_demensional_array[$row[$key]][$loop][$unset_field]);
				}
				
			}
				
			$loop ++;	
		}
		
		return $grouped_multi_demensional_array;
	}
}

if ( ! function_exists('header_details_merger'))
{
	function header_details_merger($header_array,$details_array,$foreign_key,$details_grouping_key = "",$unset_fields = array()){
		
		$merger = array();
			
		foreach($header_array as $row){
			$merger[$row[$foreign_key]]['header'] = $row;
			unset($merger[$row[$foreign_key]]['header'][$foreign_key]);
			
			foreach($unset_fields as $field){
				if(array_key_exists($field, $merger[$row[$foreign_key]]['header'])){
					unset($merger[$row[$foreign_key]]['header'][$field]);
				}
			}
			
			$loop = 0;
			
			if($details_grouping_key !== ""){
				$details = array();
				foreach($details_array as $inner_row){
					if($inner_row[$foreign_key] == $row[$foreign_key]){
						$details[$loop] = $inner_row;
						unset($details[$loop][$foreign_key]);
						
					}
					
					$loop++;
				}
				
				$merger[$row[$foreign_key]]['body'] = group_array_by_key($details,$details_grouping_key,$unset_fields);
			}else{
				
				foreach($details_array as $inner_row){
					if($inner_row[$foreign_key] == $row[$foreign_key]){
						$merger[$row[$foreign_key]]['body'][$loop] = $inner_row;
						unset($merger[$row[$foreign_key]]['body'][$loop][$foreign_key]);
					}
					
					$loop++;
				}
				
				foreach($unset_fields as $field){
					if(array_key_exists($field, $merger[$row[$foreign_key]]['body'])){
						unset($merger[$row[$foreign_key]]['body'][$field]);
					}
				}
			}
		}
		
		return $merger;
	}
}		