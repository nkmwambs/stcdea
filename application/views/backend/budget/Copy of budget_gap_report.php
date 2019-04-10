<?php
print_r($records);
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
                    	 
                    	 <table class="table table-striped">
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
                    	 			foreach($records as $office=>$values){
                    	 		?>	
                    	 			<tr>
                    	 				<td style="border-right: 1px solid black"><?=$office;?></td>
                    	 				
                    	 				<!--Staff Cost-->
                    	 				<td><?=number_format($records[$office]['budget'],2);?></td>
                    	 					<?php
                    	 						$alloc = isset($records[$office]['allocation'])?$records[$office]['allocation']:0;
                    	 					?>
                    	 					<td><?=number_format($alloc,2);?></td>
                    	 				
                    	 				<td style="border-right: 1px solid black">
                    	 					<?php
                    	 						$gap = $alloc - $records[$office]['budget'];
                    	 						echo number_format($gap,2);
                    	 					?>
                    	 				</td>
                    	 				
                    	 				<!--Thematic Cost-->
                    	 				<td></td>
                    	 				<td></td>
                    	 				<td style="border-right: 1px solid black"></td>
                    	 				
                    	 				<!--Non Thematic Cost-->
                    	 				<td></td>
                    	 				<td></td>
                    	 				<td></td>
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