<table class="table table-striped datatable">
									<thead>
										<tr>
											<th colspan="12">
												<a href="#allocate_office_<?=$office_code;?>" id="allocate_upperbtn_<?=$office_code;?>" class="btn btn-default allocate_btn"><?=get_phrase('allocate_DEA').' ('.$office_name.')';?></a>
											</th>
										</tr>
										<tr>
											<th colspan="6">
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
											
											<th><?=get_phrase("start_date");?></th>
											<th><?=get_phrase("end_date");?></th>
											<th><?=get_phrase("annual_cost");?> (A)</th>
											<th><?=get_phrase("budget_to_date");?> (B)</th>
											<th><?=get_phrase("remaining_budget");?> (C = A-B)</th>
											<!-- <th><?=get_phrase("year_expenses");?> (D)</th> -->
											<th><?=get_phrase("total_allocation");?> (E)</th>
											<th><?=get_phrase("funding_gap");?> (F = C-(E-D))</th>
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
													$gap = $remaining_budget - $deficit;
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