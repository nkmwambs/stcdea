<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo get_phrase('add_budget_line');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<?php 
							echo form_open(base_url() . 'budget/add_budget_line/', array('class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
						
						?>
												
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('thematic_area');?></label>
							<div class="col-xs-8">
								<?php
									$thematic_areas = $this->db->get_where("budget_section")->result_object()
								?>
								<select class="form-control" id="budget_section" name="budget_section">
									<option><?=get_phrase("select");?></option>
									<?php
										foreach($thematic_areas as $row){
									?>
										<option value="<?=$row->budget_section_id;?>"><?=$row->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('office');?></label>
							<div class="col-xs-8">
								<?php
									$offices = $this->db->get_where("office")->result_object()
								?>
								<select class="form-control" name="office" id="office">
									<option><?=get_phrase("select");?></option>
									<?php
										foreach($offices as $row){
									?>
										<option value="<?=$row->office_id;?>"><?=$row->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div id="form_group_staff" class="form-group hidden">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('staff');?></label>
							<div class="col-xs-8">
								<?php
									$staff = $this->db->get_where("staff")->result_object()
								?>
								<select class="form-control" id="staff" name="staff">
									<option><?=get_phrase("select");?></option>
									<?php
										foreach($staff as $row){
									?>
										<option value="<?=$row->staff_id;?>"><?=$row->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div id="form_group_account_group" class="form-group hidden">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('account_group');?></label>
							<div class="col-xs-8">
								<?php
									$budget_account_group = $this->db->get_where("budget_account_group")->result_object()
								?>
								<select class="form-control" name="account_group" id="account_group">
									<option><?=get_phrase("select");?></option>
									<?php
										foreach($budget_account_group as $row){
									?>
										<option value="<?=$row->$budget_account_group_id;?>"><?=$row->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('start_date');?></label>
							<div class="col-xs-8">
								<INPUT type="text" name="start_date" id="start_date" class="form-control datepicker" readonly="readonly" />
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('end_date');?></label>
							<div class="col-xs-8">
								<INPUT type="text" name="end_date" id="end_date" class="form-control datepicker" readonly="readonly" />
							</div>
						</div>
						
						<div id="" class="form-group">
							<table class="table">
									<thead>
										<tr>
											<th colspan="12"><?=get_phrase('monthly_spread');?></th>
										</tr>
										<tr>
											<td><?=get_phrase('clear');?></td>
											<td><?=get_phrase('total');?></td>
											<td>Jan</td>
											<td>Feb</td>
											<td>Mar</td>
											<td>Apr</td>
											<td>May</td>
											<td>Jun</td>
											<td>Jul</td>
											<td>Aug</td>
											<td>Sep</td>
											<td>Oct</td>
											<td>Nov</td>
											<td>Dec</td>
											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="btn btn-warning"><?=get_phrase("clear");?></div></td>
											<td><input type="text" class="form-control" readonly="readonly" /></td>
										<?php
											for($i=0;$i<12;$i++){
										?>
											<td><input type="text" class="form-control" /></td>
										<?php
											}
										?>
										</tr>
									</tbody>	
							</table>			
						</div>
						
						<div class="col-offset-4 col-xs-4 col-offset-4">
							<button type="submit" class="btn btn-primary btn-icon"><i class="fa fa-plus"></i><?php echo get_phrase('add');?></button>
						</div>
						
					</form>
					
					</div>
			</div>
		</div>
</div>				

<script>
	$("#budget_section").on("change",function(){
		var section_id = $(this).val();
		if(section_id == 1 && $("#form_group_staff").hasClass('hidden')){
			$("#form_group_staff").removeClass("hidden");
			$("#form_group_account_group").addClass("hidden");
		}else{
			$("#form_group_staff").addClass("hidden");
			$("#form_group_account_group").removeClass("hidden");
		}
	})
</script>	