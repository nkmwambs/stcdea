<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_user_restriction');?>
            	</div>
            </div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="btn btn-default" onclick="go_back();">Back</div>
					</div>
				</div>
				<p></p>
                <?php echo form_open(base_url() . 'account/insert_user_restriction/' , array('id'=> 'frm_add_user',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                
                <div class="form-group">
                	<label class="control-label col-xs-3">Restriction Object</label>
                	<div class="col-xs-9">
                		<select class="form-control" id="restriction_object" name="restriction_object">
                			<option value="">Select ...</option>
                			<?php
                				foreach($objects as $object){
                			?>
                				<option value="<?=$object->restriction_object_id;?>"><?=ucfirst($object->restriction_object_name);?></option>
                			<?php
								}
                			?>
                		</select>
                	</div>
                </div>
                
                <div class="form-group">
                	<label class="control-label col-xs-3">Users to Restrict</label>
                	<div class="col-xs-9">
                		<select class="form-control select2" id="restricted_users" multiple="multiple" name="restricted_users[]">
                		</select>
                	</div>
                </div>
                
                
                <div class="form-group">
                	<label class="control-label col-xs-3">Restricted Value</label>
                	<div class="col-xs-9">
                		<select class="form-control select2" id="restriction_values" class="restriction_values[]" multiple="multiple">
                			
                		</select>
                	</div>
                </div>
                
                <div class="form-group">
                	<div class="col-xs-12">
                		<button type="submit" class="btn btn-default">Save</button>
                	</div>
                </div>
                
              </form>  
                   	                 
            </div>
                                       
		</div>
	</div>
</div>

<script>

	$("#restriction_object").on('change',function(){
		
		$("#restricted_users").find('option:selected').remove();
		
		var object = $(this).val();
		
		var url = "<?=base_url();?>account/restricted_users/"+object;
		
		$.ajax({
			url:url,
			success:function(resp){
				$("#restricted_users").html(resp);
			}
		});
		
	})

	$("#restricted_users").on('change',function(){
		var object = $("#restriction_object").val();
		var url = "<?=base_url();?>account/list_restriction_values/"+object;
		var restricted_users_size = 0;
		
		if(restricted_users_size == 0){
			$.ajax({
			url:url,
			success:function(resp){
				$("#restriction_values").html(resp);
			}
		});
		}
		
		restricted_users_size = $(this).length;
		
	});	

</script>