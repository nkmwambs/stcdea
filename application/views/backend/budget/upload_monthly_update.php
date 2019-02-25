<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo get_phrase('upload_'.$update_view_name);?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<a href="#" onclick="javascript:go_back();" class="btn btn-default">Show Listing</a>
                    	
                    	<hr/>
							
							<?php 
								echo form_open(base_url() . 'budget/review_monthly_update_bulk_upload/import_excel/'.$update_view_name, array('class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
							?>
																			
							<div id="form_group_template" class="form-group">
								<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('select_excel_file');?></label>
								
								<div class="col-sm-5">
									 <a id="link_template" href="<?php echo base_url();?>uploads/excel_templates/<?=$update_view_name;?>_template.xlsx" target="_blank"
		                         		class="btn btn-info btn-sm"><i class="entypo-download"></i> Download blank excel template</a>
		                         	<br /><br />
		                         		
		                        	<input type="file" name="userfile" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
		                        
								</div>
							</div>
							
														
							<div class="form-group">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-success"><?=get_phrase("upload");?></button>
								</div>
								
							</div>

						</form>
						
					</div>
			</div>
		</div>
</div>					