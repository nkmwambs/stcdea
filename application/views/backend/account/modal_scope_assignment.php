<?php
	$scope = $this->db->get_where("scope",array("user_id"=>$param2))->row();
	$user = $this->db->get_where("user",array("user_id"=>$param2))->row();
?>
<div class="row">
	<div class="col-md-12 inner-progress"></div>
	
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-link"></i>
					<?php echo get_phrase('scope');?> : <?=$this->crud_model->get_results_by_related_id("user","user_id",$param2)->firstname;?> 
            	</div>
            </div>
			<div class="panel-body">
					<?php 
					if(sizeof($scope) > 0){	
						
					?>
					<!-- <form role="form" class="form-horizontal form-groups-bordered"> -->
					 <?php 
					 	
					 	$selected_countries = $this->db->get_where("scope_country",array("scope_id"=>$scope->scope_id))->result_object();
						$countries = $this->crud_model->get_results_by_id("country");
						//print_r($selected_countries);
					 	
					 	echo form_open(base_url() . 'account/manage_users/assign_scope/'.$param2 , array('id'=> 'frm_scope',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>		
							<div class="form-group">
								<label class="col-sm-3 control-label"><?=get_phrase("countries");?></label>
								
								<div class="col-sm-9">
									<select multiple="multiple" name="country_id[]" class="form-control multi-select">
										<?php
											
											foreach($countries as $country):
												if($country->country_id!==$user->country_id){
												$selected = "";
												foreach($selected_countries as $selected_country):
													if($selected_country->country_id === $country->country_id){
														$selected = "selected='selected';";	
													}
												endforeach;	
										?>
											<option value="<?=$country->country_id;?>" <?=$selected;?>><?=get_phrase($country->name);?></option>
										<?php 
												}
											endforeach;
										?>
										
									</select>
								</div>
							</div>
							
							
							<div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("two_way");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="two_way">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="0" <?php if($scope->two_way === "0") {echo "selected";};?> > <?=get_phrase("no");?> ><?=get_phrase("no");?></option>
	                            		<option value="1" <?php if($scope->two_way === "1") {echo "selected";}else{echo "selected";};?> ><?=get_phrase("yes");?></option>
	                            	</select>
	                            </div>
	                        </div> 
	                        
	                        <!-- <div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("strict");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="strict">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="0"  <?php if($scope->strict === "0") {echo "selected";}else{echo "selected";};?> > <?=get_phrase("no");?> </option>
	                            		<option value="1" <?php if($scope->strict === "1") {echo "selected";};?> > <?=get_phrase("yes");?> </option>
	                            	</select>
	                            </div>
	                        </div> -->   
	                        
	                        <div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("type");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="type">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="vote"  <?php if($scope->type === "vote") {echo "selected";}else{echo "selected";};?> > <?=get_phrase("voting");?> </option>
	                            		<option value="admin" <?php if($scope->type === "admin") {echo "selected";};?> > <?=get_phrase("administration");?> </option>
	                            		<option value="both" <?php if($scope->type === "both") {echo "selected";};?> > <?=get_phrase("both");?> </option>
	                            	</select>
	                            </div>
	                        </div>   	   	
							
							 <div class="form-group">
	                            <div class="col-sm-offset-3 col-sm-5">
	                                <div class="btn btn-info btn-icon submit"><i class="fa fa-save"></i><?php echo get_phrase('save');?></div>
	                            </div>
	                        </div>
							
						</form>
				
				<?php 
					}else{
						
				?>
				
					 <?php 
					 	
					 	
						$countries = $this->crud_model->get_results_by_id("country");
						//print_r($selected_countries);
					 	
					 	echo form_open(base_url() . 'account/manage_users/assign_scope/'.$param2 , array('id'=> 'frm_scope',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>		
							<div class="form-group">
								<label class="col-sm-3 control-label"><?=get_phrase("countries");?></label>
								
								<div class="col-sm-9">
									<select multiple="multiple" name="country_id[]" class="form-control multi-select">
										<?php
											
											foreach($countries as $country):
													
										?>
											<option value="<?=$country->country_id;?>" ><?=get_phrase($country->name);?></option>
										<?php 
												
											endforeach;
										?>
										
									</select>
								</div>
							</div>
							
							
							<div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("two_way");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="two_way">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="0"  > <?=get_phrase("no");?> ><?=get_phrase("no");?></option>
	                            		<option value="1" selected="selected" ><?=get_phrase("yes");?></option>
	                            	</select>
	                            </div>
	                        </div> 
	                        
	                        <div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("strict");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="strict">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="0"  selected="selected" > <?=get_phrase("no");?> </option>
	                            		<option value="1"  > <?=get_phrase("yes");?> </option>
	                            	</select>
	                            </div>
	                        </div>    
	                        
	                        <div class="form-group">
								<label class="control-label col-sm-3"><?=get_phrase("type");?></label>
	                            <div class="col-sm-9">
	                            	<select class="form-control" name="type">
	                            		<option><?=get_phrase("select");?></option>
	                            		<option value="admin" > <?=get_phrase("administration");?> </option>
	                            		<option value="vote"  selected="selected" > <?=get_phrase("voting");?> </option>
	                            		<option value="both"  > <?=get_phrase("both");?> </option>
	                            	</select>
	                            </div>
	                        </div>	   	
							
							 <div class="form-group">
	                            <div class="col-sm-offset-3 col-sm-5">
	                                <div class="btn btn-info btn-icon submit"><i class="fa fa-save"></i><?php echo get_phrase('save');?></div>
	                            </div>
	                        </div>
							
						</form>
				<?php		
					}
				?>
			</div>
			</div>
		</div>
	</div>
</div>			