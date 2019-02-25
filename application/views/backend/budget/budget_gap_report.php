<?php
//print_r($records);
?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase($page_title);?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	
                    	<div class="btn-group">
	            			<a href="<?=base_url();?>budget/budget_gap_report/<?=strtotime('-12 months',$start_year_date);?>" class="btn btn-default"><i class="fa fa-backward"></i> <?=date('Y',strtotime('-12 months',$start_year_date));?></a>
	            			<a href="#" class="btn btn-default"><?=date('Y',$start_year_date);?></a>
	            			<a href="<?=base_url();?>budget/budget_gap_report/<?=strtotime('+12 months',$start_year_date);?>" class="btn btn-default"><?=date('Y',strtotime('+12 months',$start_year_date));?> <i class="fa fa-forward"></i></a>
	            		</div>
	            		
	            		<hr />
                    	 
                    	 <table class="table table-striped datatable">
                    	 	<thead>
                    	 		<tr>
                    	 			<th rowspan="2" style="border-right: 1px solid black"><?=get_phrase('office')?></th>
                    	 			<th colspan="3" style="border-right: 1px solid black"><?=get_phrase('staff_cost');?></th>
                    	 			<th colspan="3" style="border-right: 1px solid black"><?=get_phrase('thematic_cost');?></th>
                    	 			<th colspan="3"><?=get_phrase('non_thematic_cost');?></th>
                    	 		</tr>
                    	 		<tr>
                    	 			<th><?=get_phrase('total_budget');?></th>
                    	 			<th><?=get_phrase('total_allocation');?></th>
                    	 			<th style="border-right: 1px solid black"><?=get_phrase('funding_gap');?></th>
                    	 			
                    	 			<th><?=get_phrase('total_budget');?></th>
                    	 			<th><?=get_phrase('total_allocation');?></th>
                    	 			<th style="border-right: 1px solid black"><?=get_phrase('funding_gap');?></th>
                    	 			
                    	 			<th><?=get_phrase('total_budget');?></th>
                    	 			<th><?=get_phrase('total_allocation');?></th>
                    	 			<th><?=get_phrase('funding_gap');?></th>
                    	 		</tr>
                    	 	</thead>
                    	 	<tbody>
                    	 		<?php
                    	 			
                    	 			$start_date = date('Y-m-01',$start_year_date);
                    	 			
                    	 			foreach($records as $office=>$values){
                    	 				
										$staff_cost_budget = isset($values['budget']['staff_cost'])?$values['budget']['staff_cost']:0;
										$staff_cost_alloc = isset($values['allocation']['staff_cost'])?$values['allocation']['staff_cost']:0;
										
										$thematic_cost_budget = isset($values['budget']['thematic_cost'])?$values['budget']['thematic_cost']:0;
										$thematic_cost_alloc = isset($values['allocation']['thematic_cost'])?$values['allocation']['thematic_cost']:0;
										
										$non_thematic_cost_budget = isset($values['budget']['non_thematic_cost'])?$values['budget']['non_thematic_cost']:0;
										$non_thematic_cost_alloc = isset($values['allocation']['non_thematic_cost'])?$values['allocation']['non_thematic_cost']:0;
										
                    	 				$staff_color = '';
                    	 				//if($staff_cost_budget == 0) $staff_color = 'style="background-color:#CC6600";';
										
										$thematic_cost = '';
                    	 				//if($thematic_cost_budget == 0) $thematic_cost = 'style="background-color:#CC6600";';
                    	 		
                    	 				$non_thematic_cost = '';
                    	 				//if($non_thematic_cost_budget == 0) $non_thematic_cost = 'style="background-color:#CC6600";';
                    	 				
                    	 				$office_id = $this->db->get_where('office',array('name'=>$office))->row()->office_id;
										
                    	 		?>	
                    	 			<tr>
                    	 				<td style="border-right: 1px solid black"><?=$office;?></td>
                    	 				
                    	 				<td <?=$staff_color;?> ><?=number_format($staff_cost_budget,2);?></td>
                    	 				<td <?=$staff_color;?> ><?=number_format($staff_cost_alloc,2);?></td>
                    	 				<td 
                    	 					<?=$staff_color;?>  style="border-right: 1px solid black"><?=number_format($staff_cost_budget - $staff_cost_alloc,2);?> 
                    	 					<a href="<?=base_url();?>budget/show_accounts_allocation/<?=$office_id;?>/<?=$start_date;?>/staff_cost" 
                    	 						class="fa fa-plus-circle"></a> 
                    	 				</td>
                    	 				
                    	 				<td <?=$thematic_cost;?> ><?=number_format($thematic_cost_budget,2);?></td>
                    	 				<td <?=$thematic_cost;?> ><?=number_format($thematic_cost_alloc,2);?></td>
                    	 				<td 
                    	 					<?=$thematic_cost;?> style="border-right: 1px solid black"><?=number_format($thematic_cost_budget - $thematic_cost_alloc ,2);?> 
                    	 					<a href="<?=base_url();?>budget/show_accounts_allocation/<?=$office_id;?>/<?=$start_date;?>/thematic_cost" 
                    	 						class="fa fa-plus-circle"></a> 
                    	 				</td>
                    	 				
                    	 				<td <?=$thematic_cost;?> ><?=number_format($non_thematic_cost_budget,2);?></td>
                    	 				<td <?=$thematic_cost;?> ><?=number_format($non_thematic_cost_alloc,2);?></td>
                    	 				<td 
                    	 					<?=$thematic_cost;?> ><?=number_format($non_thematic_cost_budget - $non_thematic_cost_alloc,2)?> 
                    	 					<a href="<?=base_url();?>budget/show_accounts_allocation/<?=$office_id;?>/<?=$start_date;?>/non_thematic_cost" 
                    	 						class="fa fa-plus-circle"></a> 
                    	 				</td>
                       	 			</tr>
                    	 		<?php
									}
                    	 		?>
                    	 	</tbody>
                    	 </table>
					</div>
				</div>
			</div>
	</div>	
	
	<script>
		$(document).ready(function(){
			
		});
	</script>			