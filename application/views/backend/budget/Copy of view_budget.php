
<div class="<?=get_access('add_'.$budget_type.'_budget_line','view_'.$budget_type.'_budget');?> row">
	<div class="col-xs-12">
		<a href="<?=base_url();?>Budget/add_budget_line" class="btn btn-default"><?=get_phrase("add_bugdet_line");?> <i class="fa fa-plus-circle"></i></a>
		<a href="<?=base_url();?>Budget/upload_budget/<?=$budget_type;?>" class="btn btn-default"><?=get_phrase("upload_budget_worksheet");?> <i class="entypo-upload"></i></a>
		<a href="#" onclick="javascript:go_back();" class="btn btn-default">Go Back<i class="fa fa-reply"></i></a>
	</div>
</div>

<hr class="<?=get_access('add_'.$budget_type.'_budget_line','view_'.$budget_type.'_budget');?>" />

<div class="row">
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

<hr/>

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
											<th colspan="14">
												<a href="#allocate_office_<?=$office_code;?>" id="allocate_upperbtn_<?=$office_code;?>" class="btn btn-default allocate_btn"><?=get_phrase('allocate_DEA').' ('.$office_name.')';?></a>
											</th>
										</tr>
										<tr>
											<th colspan="7">
												<?=get_phrase('office_name')?>: <?=$office_name;?>	
											</th>
											<th colspan="7">
												<?=get_phrase('office_code')?>: <?=$this->crud_model->get_field_value("office","office_id",$office_code,"office_code");?>												
											</th>

										</tr>
										<tr>
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
											<th><?=get_phrase("start_date");?></th>
											<th><?=get_phrase("end_date");?></th>
											<th><?=get_phrase("annual_cost");?> (A)</th>
											<th><?=get_phrase("budget_to_date");?> (B)</th>
											<th><?=get_phrase("remaining_budget");?> (C = A-B)</th>
											<!-- <th><?=get_phrase("year_expenses");?> (D)</th> -->
											<th><?=get_phrase("total_allocation");?> (E)</th>
											<th><?=get_phrase("funding_gap");?> (F = E-A)</th>
											<th class="<?=get_access('show_'.$budget_type.'_action','view_'.$budget_type.'_budget');?>"><?=get_phrase("action");?></th>
						
										</tr>
									</thead>
									<tbody>
										<?php
											$sum_gap = 0;
											foreach($data as $row){
										?>
										<tr>
										<?php		
												foreach($budget_section_fields as $fields){
													$table_id = $this->db->get_where('budget',
														array('budget_id'=>$row->budget_id))
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
											<td><?=$row->description;?></td>
											<td><?=$row->start_date;?></td>
												<td><?=$row->end_date;?></td>
												<?php
													$annual_cost = $this->db->select_sum('amount')->get_where("budget_spread",array("budget_id"=>$row->budget_id))->row()->amount; 
												?>
												<td><?=number_format($annual_cost,2);?></td>
												<?php
													$budget_to_date = $this->budget_model->get_budget_to_date($row->budget_id,$month);
													$remaining_budget = $annual_cost - $budget_to_date;
													// $year_expenses = 0;
												?>
												<td><?=number_format($budget_to_date,2);?></td>
												<td><?=number_format($remaining_budget,2);?></td>
												<!-- <td><?=number_format($year_expenses,2);?></td> -->
												<?php
													$total_allocation = $this->db->select_sum('amount')->get_where("allocation",array("budget_id"=>$row->budget_id))->row()->amount;
													$deficit =$total_allocation- $budget_to_date;
													$gap = $deficit - $remaining_budget;
												?>
												<td><?=number_format($total_allocation,2);?></td>
												<td>
													<?=number_format($gap,2);?>
												</td>
												<td class="<?=get_access('show_'.$budget_type.'_action','view_'.$budget_type.'_budget',0);?>">
		
													<div class="btn-group">
									                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
									                        <?php echo get_phrase('action');?> <span class="caret"></span>
									                    </button>
									                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
									                   		
									                   		<li class="<?=get_access('edit_'.$budget_type.'_budget_line','show_'.$budget_type.'_action');?>">
									                   			<a class="" href="<?=base_url();?>budget/edit_budget_line/<?=$row->budget_id;?>">
									                               <i class="fa fa-trash"></i>
									                               		<?php echo get_phrase('edit_budget_line');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="<?=get_access('edit_'.$budget_type.'_budget_line','show_'.$budget_type.'_action');?> divider"></li>
									                   		
									                   		<!-- <li class="<?=get_access('allocate_'.$budget_type.'_DEA','show_'.$budget_type.'_action');?>">
									                             <a class="" href="<?=base_url();?>budget/allocate_dea/<?=$row->budget_id;?>/<?=strtotime($selected_date);?>">
									                               <i class="fa fa-cloud-download"></i>
									                               		<?php echo get_phrase('allocate_DEA');?>
									                             </a>
									                        </li>
									             							                        
									                         <li class="<?=get_access('allocate_'.$budget_type.'_DEA','show_'.$budget_type.'_action');?> divider"></li>
									                         -->
									                        <li class="<?=get_access('show_'.$budget_type.'_budget_spread','show_'.$budget_type.'_action');?>">
									                        	<a classs="action" href="#" onclick="showAjaxModal('<?=base_url();?>modal/popup/modal_budget_spread/<?=$row->budget_id;?>');">
									                            	<i class="fa fa-list"></i>
																		<?php echo get_phrase('show_budget_spread');?>
									                               	</a>
									                        </li>
									                        
									                     </ul>
									                  </div> 
												</td>
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
								<hr />
								
								<div class="row">
									<div class="col-xs-12">
										<a href="#allocate_office_<?=$office_code;?>" id="allocate_lowerbtn_<?=$office_code;?>" class="btn btn-default allocate_btn"><?=get_phrase('allocate_DEA').' ('.$office_name.')';?></a>
									</div>
								</div>
								
								<div class="hidden allocate_section" id="allocate_office_<?=$office_code;?>">
									
								</div>
								
								<hr />
						<?php
							}
						}
						?>
	</div>
</div>

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
	
	$(".allocate_btn").on('click',function(){
		//alert($(this).attr('id'));
		var this_btn_id = $(this).attr('id');
		var office_id = this_btn_id.split('_')[2];
		var allocate_section = $("#allocate_office_"+office_id);
		var allocate_sections = $(".allocate_office");
		
		var url = '<?=base_url();?>budget/allocate_dea/'+office_id+'/<?=$budget_type;?>/<?=strtotime($selected_date);?>/<?=$current_date;?>'
		
		$.ajax({
			url:url,
			beforeSend:function(){
				
			},
			success:function(resp){
				$(".allocate_section").each(function(){
					if(!$(this).hasClass("hidden")){
						$(this).addClass('hidden');
					}
				});
 				
				allocate_section.removeClass('hidden');
 				
				allocate_section.html(resp);
				
			},
			error:function(){
				
			}
		});
		
	})
	
</script>