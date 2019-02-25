<?php
//print_r($budget_line);
extract((array)$budget_line[0]);
//echo array_sum(array_column($budget_line, 'amount'));
?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('edit_budget_line');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<a href="#" onclick="javascript:go_back();" class="btn btn-default">Show Listing</a>
                    	<hr />
                    	
                    	<?php 
							echo form_open(base_url() . 'budget/update_budget_line/update/'.$budget_id, array('id'=>'frm_add_budget', 'class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
						
						?>
												
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('budget_section');?></label>
							<div class="col-xs-8">
								<?php
									$thematic_areas = $this->db->get_where("budget_section")->result_object()
								?>
								<select class="form-control" id="budget_section" name="budget_section_id" disabled="disabled">
									<option value=""><?=get_phrase("select");?></option>
									<?php
										foreach($thematic_areas as $row){
									?>
										<option value="<?=$row->budget_section_id;?>" <?php if($budget_line[0]->budget_section_id == $row->budget_section_id) echo "selected";?> ><?=$row->name;?></option>
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
								<select class="form-control" name="office_code" id="office" disabled="disabled">
									<option value=""><?=get_phrase("select");?></option>
									<?php
										foreach($offices as $row){
									?>
										<option value="<?=$row->office_id;?>" <?php if($budget_line[0]->office_code == $row->office_id) echo "selected";?> ><?=$row->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div id="form_group_staff" class="form-group hidden">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('staff');?></label>
							<div class="col-xs-8">
								
								<select class="form-control" id="staff" name="related_table_primary_key_value">
									<option value="<?=$related_table_primary_key_value;?>">
										<?=$this->db->get_where($related_table,array($related_table."_id"=>$related_table_primary_key_value))->row()->name;?>
									</option>
									
								</select>
							</div>
						</div>
						
						<div id="form_group_account_group" class="form-group hidden">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('account_group');?></label>
							<div class="col-xs-8">
								
								<select class="form-control" name="account_group" id="account_group">
									<option value="<?=$related_table_primary_key_value;?>">
										<?=$this->db->get_where($related_table,array($related_table."_id"=>$related_table_primary_key_value))->row()->name;?>
									</option>
								</select>
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('narrative');?></label>
							<div class="col-xs-8">
								<INPUT type="text" name="description" value="<?=$description;?>" id="narrative" class="form-control" />
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('start_date');?></label>
							<div class="col-xs-8">
								<INPUT type="text" name="start_date" data-format='yyyy-mm-dd' id="start_date" value="<?=$start_date;?>" class="form-control datepicker" readonly="readonly" />
							</div>
						</div>
						
						<div id="" class="form-group">
							<label for="" class="col-xs-4 control-label"><?php echo get_phrase('end_date');?></label>
							<div class="col-xs-8">
								<INPUT type="text" name="end_date" data-format='yyyy-mm-dd' id="end_date" value="<?=$end_date;?>" class="form-control datepicker" readonly="readonly" />
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-xs-12">
								<div class="btn btn-default" id="btn_spread">Spread First Month Value</div>
							</div>
						</div>
						
						<div id="" class="form-group">
							<table class="table" id="table_spread">
									<thead>
										<tr>
											<th><?=get_phrase('month');?></th>
											<th><?=get_phrase('amount');?></th>
										</tr>
										
									</thead>
									<tbody>
										
										<?php
											for($i=1;$i<13;$i++){
												$year = date('Y');
												$month_count = $i;
												$date_raw = new DateTime();
												$date = $date_raw->setDate($year,$month_count,1)->format('M');
												$key = $i-1;
										?>
											<tr>
												<td><?=$date;?><td><input type="number" name="spread[]" value="<?=$budget_line[$key]['amount'];?>" class="form-control spread" /></td>
											</tr>
										<?php
											}
										?>
										
									</tbody>	
									<tfoot>
										<tr>
											<td><?=get_phrase('total')?></td>
											<td><input type="text" name="total" class="form-control" id="total" value="<?=array_sum(array_column($budget_line, 'amount'));?>" readonly="readonly" /></td>
										</tr>
									</tfoot>
							</table>			
						</div>
						
						<div class="form-group">
							<div class="col-offset-4 col-xs-4 col-offset-4">
								<button type="submit" class="btn btn-primary btn-icon"><i class="fa fa-plus"></i><?php echo get_phrase('edit');?></button>
							</div>
						</div>
						
					</form>
					
					</div>
			</div>
		</div>
</div>				

<script>
	
	$(document).ready(function(){
		
		var budget_section_id = <?=$budget_section_id;?>;
		
		change_budget_section($("#budget_section"));
	});

	$("#btn_spread").on('click',function(){
		var first_month_amount = $("#table_spread tbody").find('tr:eq(0) input').val();
		
		$("#table_spread tbody").find('input[type=number]').val(first_month_amount);
		
		total = 0;
		
		$(".spread").each(function(){
			total += parseFloat($(this).val());
		});
		
		$("#total").val(total);
	})
	
	$("#budget_section").on("change",function(){
		change_budget_section($(this));
	})
	
	function change_budget_section(budget_section_id_elem){
		var section_id = budget_section_id_elem.val();
		if(section_id == 1){
			$("#form_group_staff").removeClass("hidden");
			$("#form_group_account_group").addClass("hidden");
			$("#account_group").prop('name','');	
			$("#staff").prop('name','related_table_primary_key_value');			
		}else{
			$("#form_group_staff").addClass("hidden");
			$("#form_group_account_group").removeClass("hidden");
			$("#staff").prop('name','');	
			$("#account_group").prop('name','related_table_primary_key_value');
		}
	}
	
	$("#office").on('change',function(){
		var section_id = $("#budget_section").val();
		var office_id = $("#office").val();
		
		$("#staff").find("option:gt(0)").remove();
		
		<?php $staff_obj = json_encode($staff);?>
		
		var staff_obj = <?=$staff_obj;?>;
		
		if(section_id == 1){
			$("#form_group_staff").removeClass("hidden");
			
			var options = "";
			
			$.each(staff_obj,function(indx,elem){
				if(office_id == elem.office_id){
					options = "<option value="+elem.staff_id+">"+elem.name+"</option>";
				}
			})
			
			$("#staff").append(options);
			
		}else{
			$("#form_group_staff").addClass("hidden");
		}
	})
	
	
	$(".spread").on("change",function(){
		
		//Decline an empty number
		if($(this).val() < 1)  $(this).val('0')
		
		//Calculate the total spread
		var total = 0;
		
		$(".spread").each(function(){
			total += parseFloat($(this).val());
		});
		
		$("#total").val(total);
	});
	
	$("#frm_add_budget").on('submit',function(ev){
		
		if($("#budget_section").val()>1){
			alert("Only post staff cost!");
			return false;
		}
		
		var frm = $(this);
		var url = frm.attr('action');
		var data = frm.serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				alert(resp);
				//$("input[type=number]").val("0");
				//$("input[type=text],select").val(null);
			},
			error:function(){
				$("#overlay").css("display","none");
				alert("Failed");
			}
		});
		
		ev.preventDefault();
	});
</script>	