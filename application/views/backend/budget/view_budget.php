<?php
ini_set('memory_limit', '1024M');

$view_budget_restriction = $this->db->get_where('field_restriction',
array('role_id'=>$this->session->role_id,'restricted_to_object'=>'view_budget'))->num_rows();

if($view_budget_restriction ==  0){

//echo $test;

?>
<div class="row">
	<div class="col-xs-12">
		<?php
			echo form_open(base_url() . 'budget/view_budget/'.$budget_type, array('class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
			//echo $office_id;
		?>
			<div class="form-group">
				<label class="col-xs-4">Field Office</label>
				<div class="col-xs-6">
					<select name="office_id" class="form-control">
						<option value=""><?=get_phrase('select');?>...</option>
						<?php
							foreach($offices as $office){
						?>
							<option value="<?=$office->office_id;?>" <?php if($office_id == $office->office_id) echo "selected";?> ><?=$office->name;?></option>
						<?php
							}
						?>
					</select>
				</div>
				<div class="col-xs-2">
					<button type="submit" class="btn btn-default">Go</button>
				</div>
			</div>
		</form>
	</div>
</div>

<hr>

<div class="<?=get_access('add_'.$budget_type.'_budget_line','view_'.$budget_type.'_budget');?> row">
	<div class="col-xs-12">
		<!-- <a href="<?=base_url();?>Budget/add_budget_line" class="btn btn-default"><?=get_phrase("add_bugdet_line");?> <i class="fa fa-plus-circle"></i></a>
		 -->
		<a href="<?=base_url();?>Budget/upload_budget/<?=$budget_type;?>" class="btn btn-default"><?=get_phrase("upload_budget_worksheet");?> <i class="entypo-upload"></i></a>
		<a href="#" onclick="javascript:go_back();" class="btn btn-default">Go Back<i class="fa fa-reply"></i></a>
	</div>
</div>

<hr class="<?=get_access('add_'.$budget_type.'_budget_line','view_'.$budget_type.'_budget');?>" />


<?php
}

if(isset($load_budget)){
	//echo $load_budget;
?>


<!-- <div class="row">
	<div class="col-xs-12">
		<div class="btn btn-default scroll_month scroll_minus"><i class="fa fa-backward"></i></div>
		<div class="btn btn-default scroll_month current_month" id="current_month"><?=date('F Y',strtotime($selected_date));?></div>
		<div class="btn btn-default scroll_month scroll_plus"><i class="fa fa-forward"></i></div>
		<input type="hidden" id="scroll_count" value="<?=isset($scroll_count)?$scroll_count:0;?>" />
		<div class="btn btn-default" id="scroll">Go</div>
		<?php $current_date = isset($current_month)?$current_month:strtotime($selected_date);?>
		<div class="btn btn-default" id="reset">Reset to <?=date("F Y",$current_date);?></div>
	</div>
</div>

<hr/> -->

<div class="row">
	<div class="col-xs-12">
		
			<?php
				if(count($grouped) == 0){
			?>
				<div class="well" style="text-align: center;">No Data Found</div>
			<?php		
				} else{
					foreach($grouped as $office_code=>$data){
						$office_name = $this->crud_model->get_field_value("office","office_id",$office_code,"name");
			?>		
					<div class="office_budget_holder_<?=$office_code;?>">
							<table class="table table-striped datatable">
									<thead>
										<tr>
											<th colspan="16">
												<a href="<?php echo base_url("Budget/allocate_dea_spread/".$office_id."/".$budget_type."/".strtotime('first day of january',strtotime(date('Y-m-d')))."/".strtotime('last day of december',strtotime(date('Y-m-d'))));?>" id="" class="btn btn-default"><?=get_phrase('allocate_DEA').' ('.$office_name.')';?></a>
											</th>
										</tr>
										<tr>
											<th colspan="8">
												<?=get_phrase('office_name')?>: <?=$office_name;?>	
											</th>
											<th colspan="8">
												<?=get_phrase('office_code')?>: <?=$this->crud_model->get_field_value("office","office_id",$office_code,"office_code");?>												
											</th>

										</tr>
										<tr>
											<th><?=get_phrase('global_key');?></th>
											<th><?=get_phrase('forecast_period');?></th>
											<!--Budget type dependant fields -->
											<?php
												foreach($budget_section_fields as $fields){
											?>
												<th><?=$fields->name;?></th>
											<?php
												}
											?>											
											<!--Budget type dependant fields -->
											<th><?=get_phrase("description");?></th>
											<!-- <th><?=get_phrase("start_date");?></th>
											<th><?=get_phrase("end_date");?></th> -->
											<th><?=get_phrase("annual_cost");?></th>
											<?php
												for($i=1;$i<13;$i++){
											?>
												<th><?=get_phrase('month')." ".$i;?> </th>
											<?php
												}
											?>
											
											
										</tr>
									</thead>
									<tbody>
										<?php
											$sum_gap = 0;
											foreach($data as $budget_id=>$row){
										?>
										<tr>
												<td><?=$row['header']['global_key'];?></td>
												<td><?=$row['header']['forecast_period'];?></td>
										<?php		
												foreach($budget_section_fields as $fields){
													$table_id = $this->db->get_where('budget',
														array('budget_id'=>$budget_id))
														->row()->related_table_primary_key_value;
														
													$related_table = $this->db->get_where('budget_section',array('name'=>$page_title))
													->row()->related_table;	
										?>
												<td><?=
														$this->crud_model->get_field_value(
														$related_table,
														$related_table."_id",
														$table_id,
														$fields->related_table_return_fields);
												?></td>
										<?php		
												}	
										?>
											
											<td><?=$row['header']['description'];?></td>
											<!-- <td><?=$row['header']['start_date'];?></td>
											<td><?=$row['header']['end_date'];?></td> -->
												<?php
													$annual_cost = $this->db->select_sum('amount')->get_where("budget_spread",array("budget_id"=>$budget_id))->row()->amount; 
												?>
												<td><?=number_format($annual_cost,2);?></td>
												
												<?php
													foreach($row['spread'] as $month_budget){
												?>
													<td><?=$month_budget;?></td>
												<?php
													}
												?>
												
												
											</tr>	
										<?php
												//$sum_gap +=$gap;
											}
										?>
									</tbody>
									<tfoot>
										<!-- <tr>
											<td colspan="11"><?=get_phrase("sum_of_funding_gap");?></td>
											<td><?=number_format($sum_gap,2);?></td>
											<td></td>
										</tr> -->
									</tfoot>
								</table>
						</div>		
								<!-- <hr />
								
								<div class="row">
									<div class="col-xs-12">
										<a href="#allocate_office_<?=$office_code;?>" id="allocate_lowerbtn_<?=$office_code;?>" class="btn btn-default allocate_btn"><?=get_phrase('allocate_DEA').' ('.$office_name.')';?></a>
									</div>
								</div>
								
								<div class="hidden allocate_section" id="allocate_office_<?=$office_code;?>">
									
								</div> -->
								
								
						<?php
							}
						}
						?>
	</div>
</div>

<?php
}
?>

<script>
	$(document).ready(function(){
	
		$("th, td").attr('nowrap','nowrap');
	
	});
	
	$(".scroll_month").on('click',function(){
		var month = $(this).attr('id');
		var steps = 0;
		
		if($(this).hasClass('scroll_minus')){
			steps = parseInt($("#scroll_count").val()) - 1;
		}else{
			steps = parseInt($("#scroll_count").val()) + 1;
		}
		
		$("#scroll_count").val(steps);
		
		$("#current_month").html("<?=date('F Y',$current_date);?> ("+steps+")");
		
		
	});
	
	$("#scroll,#reset").on('click',function(){
		var scroll_count = $("#scroll_count").val();
		if($(this).attr('id') == 'reset'){
			scroll_count = 0;
		}
		var current_month = "<?=$current_date;?>";
		var url = "<?=base_url();?>budget/view_budget_scroll/<?=$budget_type;?>/"+current_month+"/"+scroll_count;		
		
		
		$.ajax({
			url:url,
			beforeSend:function(){
				$("#overlay").css('display','block');
			},
			success:function(resp){
				$("#overlay").css('display','none');
				$('.page-content').html(resp);
			},
			error:function(){
				
			}
		});
	})
	
	$(".action").on('click',function(ev){
		alert("Feature Under Construction!");
		ev.preventDefault();
	})		
	
	var spinner = $("#spinner" ).inputSpinner();
	

	
</script>