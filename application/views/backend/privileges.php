<style>
<?php
	
	if($this->session->is_super_user == 0){

		$entitlements = $this->db->get("entitlement")->result_object();
		
		foreach($entitlements as $entitlement):
		
		echo ".".$entitlement->name."{display:none;}";
	
		endforeach;
	
		foreach($entitlements as $entitlement):
			if($this->crud_model->user_privilege($this->session->profile_id,$entitlement->name)){
	
				echo ".".$entitlement->name."{display:block;}";
		 	
				
				if($entitlement->derivative_id !== 0){
					$first_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$entitlement->derivative_id))->row();
				
					echo ".".$first_parent->name."{display:block;}";
					
					if($first_parent->derivative_id !== 0){
							$second_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$first_parent->derivative_id))->row();
						
							echo ".".$second_parent->name."{display:block;}";
						
							if($second_parent->derivative_id !== 0){
								$third_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$second_parent->derivative_id))->row();
								
									echo ".".$third_parent->name."{display:block;}";
									
									if($third_parent->derivative_id !== 0){
										$fourth_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$third_parent->derivative_id))->row();
									
											echo ".".$fourth_parent->name."{display:block;}";	
											
											if($fourth_parent->derivative_id !== 0){
												$fifth_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$fourth_parent->derivative_id))->row();
									
												echo ".".$fifth_parent->name."{display:block;}";
												
												if($fifth_parent->derivative_id !== 0){
													$sixth_parent = $this->db->get_where("entitlement",array("entitlement_id"=>$fifth_parent->derivative_id))->row();
									
													echo ".".$sixth_parent->name."{display:block;}";
												}
											}	
									}
								
							}
					}
				}
				
				
			}
		endforeach;
	}
?> 

</style>