<?php
	$profile = $this->crud_model->get_results_by_id("profile",$param2);
?>
<div class="row">
	<div class="col-md-12 inner-progress"></div>
	
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-link"></i>
					<?php echo get_phrase('assign_privileges');?> : <?=$profile->name;?> 
            	</div>
            </div>
			<div class="panel-body">
				<?php 
					$assigned_privileges = $this->crud_model->get_entitlement_by_profile_id($profile->profile_id);  
					$list_privileges = $this->crud_model->get_results_by_id("entitlement");
					
				?>
					<!-- <form role="form" class="form-horizontal form-groups-bordered"> -->
					 <?php echo form_open(base_url() . 'account/profiles/assign_privileges/'.$profile->profile_id , array('id'=> 'frm_assign',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>		
							<div class="form-group">
								<label class="col-sm-3 control-label"><?=get_phrase("assign_privileges");?></label>
								
								<div class="col-sm-9">
									<select multiple="multiple" name="privilege_id[]" class="form-control multi-select">
										<?php
											foreach($list_privileges as $privilege):
												$selected = "";
												foreach($assigned_privileges as $assigned):
													if($privilege->entitlement_id === $assigned->entitlement_id){
														$selected = "selected='selected';";	
													}
												endforeach;	
										?>
											<option value="<?=$privilege->entitlement_id;?>" <?=$selected;?>><?=get_phrase($privilege->name);?></option>
										<?php 
												
											endforeach;
										?>
										
									</select>
								</div>
							</div>
							
							 <div class="form-group <?php if($this->session->profile_id == $profile->profile_id) echo "self_assign_privilege";?>">
	                            <div class="col-sm-offset-3 col-sm-5">
	                                <div class="btn btn-info btn-icon submit edit_entitlement"><i class="fa fa-save"></i><?php echo get_phrase('save');?></div>
	                            </div>
	                        </div>
							
						</form>
				
				<?php 
				
				?>
			</div>
		</div>
	</div>
</div>			