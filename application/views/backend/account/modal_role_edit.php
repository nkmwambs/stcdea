<?php 
$edit_data		=	$this->db->get_where('role' , array('role_id' => $param2) )->result_object();
foreach ( $edit_data as $row):
?>
<div class="row">
	
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_role');?>
            	</div>
            </div>
			<div class="panel-body">
                    <?php echo form_open(base_url() . 'account/roles/role_edit/'.$row->role_id , array('id'=> 'frm_edit_country',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                                                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('role');?></label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control" name="name" value="<?=$row->name;?>" required="required" placeholder="<?=get_phrase("role_title");?>"/>
                                </div>
                            </div>
                            
                           <div class="form-group">
                           		<label class="col-sm-3 control-label"><?php echo get_phrase('department');?></label>
                           		<div class="col-sm-5">
                           			<select class="form-control" name="department_id">
                           				<option><?=get_phrase("select");?></option>
                           				
                           				<?php 
                           					$departments = $this->crud_model->get_results_by_id("department");
											
											foreach($departments as $department):
                           				?>
                           					<option value="<?=$department->department_id;?>" <?php if($department->department_id === $row->department_id) echo "selected";?>><?=$department->name;?></option>
                           				
                           				<?php 
                           					endforeach;
                           				?>
                           			</select>
                           		</div>
                           </div>
                           
                           <div class="form-group">
                           		<label class="col-sm-3 control-label"><?php echo get_phrase('contribution');?></label>
                           		<div class="col-sm-5">
                           			<select class="form-control" name="contribution">
                           				<option><?=get_phrase("select");?></option>
                           				<option value="staff" <?php if($row->contribution==="staff") echo "selected";?>><?=get_phrase("staff");?></option>
                           				<option value="manager" <?php if($row->contribution==="manager") echo "selected";?>><?=get_phrase("manager");?></option>
                           			</select>
                           		</div>
                           </div>
                            
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <div class="btn btn-info btn-icon submit"><i class="fa fa-save"></i><?php echo get_phrase('save');?></div>
                              </div>
							</div>
          
               </form>
               
               
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>

<script>
$("#test").click(function(){})

</script>