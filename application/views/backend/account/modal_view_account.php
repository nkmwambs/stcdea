<?php
$user = $this->db->get_where("user",array("user_id"=>$param2))->row();
?>
<div class="row">
	<div class="col-md-12 inner-progress"></div>
	
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-user"></i>
					<?php echo get_phrase('account');?> : <?=$this->crud_model->get_results_by_related_id("user","user_id",$param2)->firstname;?> 
            	</div>
            </div>
			<div class="panel-body">
				<table class="table">
					<thead></thead>
					<tbody>
					<tr>
						<td><?=get_phrase("status");?></td>
						<td><?=$user->auth="1"? get_phrase("active"):get_phrase("suspended");?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("full_name");?></td>
						<td><?=$user->firstname." ".$user->lastname;?></td>
					</tr>
					
					
					<tr>
						<td><?=get_phrase("gender");?></td>
						<td><?=$user->gender;?></td>
					</tr>
					
					
					<tr>
						<td><?=get_phrase("country");?></td>
						<td><?=$this->crud_model->get_type_name_by_id("country",$user->country_id);?></td>
					</tr>
										
					<tr>
						<td><?=get_phrase("email");?></td>
						<td><?=$user->email;?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("phone");?></td>
						<td><?=$user->phone;?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("employee_number");?></td>
						<td><?=$user->employee_id;?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("role");?></td>
						<td><?=$this->crud_model->get_type_name_by_id("role",$user->role_id);?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("profile");?></td>
						<td><?=$this->crud_model->get_type_name_by_id("profile",$user->profile_id);?></td>
					</tr>
					
					
					<tr>
						<td><?=get_phrase("scope");?></td>
						<td>
							<?php
								$scope = $this->db->get_where("scope",array("user_id"=>$param2));
								
								if($scope->num_rows() > 0 ) {
									$arr = array();			
									$scope_countries = $this->db->get_where("scope_country",array("scope_id"=>$scope->row()->scope_id))->result_object();
									//print_r($scope_countries);
									foreach($scope_countries as $scope_country):
										
										$arr[] = $this->crud_model->get_type_name_by_id("country",$scope_country->country_id);
										
									endforeach;
									//print_r($arr);
									echo implode(",", $arr);
								}else{
									echo $this->crud_model->get_type_name_by_id("country",$user->country_id);
								}
							?>		
						</td>
					</tr>
					
					<tr>
						<td><?=get_phrase("department");?></td>
						<?php $department_id = $this->db->get_where("role",array("role_id"=>$user->role_id))->row()->department_id;?>
						<td><?=$this->crud_model->get_type_name_by_id("department",$department_id);?></td>
					</tr>
					
					<tr>
						<td><?=get_phrase("teams");?></td>
						<td>
							<?php 
									$team_array = array();
									$teamset = $this->db->get_where("teamset",array("user_id"=>$param2))->result_object();
									foreach($teamset as $team){
										$team_array[] = $this->db->get_where("team",array("team_id"=>$team->team_id))->row()->name;
									}
									
									$team_str = implode(",", $team_array);
									
									if($team_str==""){
										$team_str = get_phrase("not_set");
									}
									echo $team_str;
							;?>
						</td>
					</tr>
					
					</tbody>
				</table>	
			</div>
		</div>
	</div>
</div>				