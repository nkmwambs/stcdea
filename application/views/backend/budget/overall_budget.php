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
            <div class="panel-body" style="overflow-y:auto;">
            		
            		<div class="btn-group">
            			<a href="<?=base_url();?>budget/overall_budget/<?=strtotime('-12 months',$start_year_date);?>" class="btn btn-default"><i class="fa fa-backward"></i> <?=date('Y',strtotime('-12 months',$start_year_date));?></a>
            			<a href="#" class="btn btn-default"><?=date('Y',$start_year_date);?></a>
            			<a href="<?=base_url();?>budget/overall_budget/<?=strtotime('+12 months',$start_year_date);?>" class="btn btn-default"><?=date('Y',strtotime('+12 months',$start_year_date));?> <i class="fa fa-forward"></i></a>
            		</div>
            		
            		<hr />
            		
                    <table class="table table-striped">
                    	<thead>
                    		<tr>
                    			<th><?=get_phrase('budget_type');?></th>
                    			<?php
                    				$months = range(1,12);
									
									foreach($months as $month){
                    			?>
                    					<th style="text-align: right;"><?=get_phrase('month').' '.$month;?></th>
                    			<?php
									}
                    			?>
                    			<th style="text-align: right;"><?=get_phrase('year_total')?></th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    		<?php
                    			
                    			foreach($records as $bugdet_type=>$field_offices){
                    		?>
                    			<tr>
                    				<td><i style="cursor: pointer;" id="<?=strtolower(str_replace(" ", "_", $bugdet_type));?>" 
                    					class="fa fa-eye-slash show_hide_field_row"></i> <?=$bugdet_type;?></td>
                    				<?php
                    					$total = 0;
                    					for($i=1;$i<13;$i++){
                    						$total += array_sum(array_column($field_offices, $i));
                    				?>
                    					<td style="text-align: right;"><?php echo number_format(array_sum(array_column($field_offices, $i)),2);?></td>
                    				<?php
										}
                    				?>
                    				<td style="text-align: right;"><?php echo number_format($total,2);?></td>
                    			</tr>
                    			<tr class="field_office_row" style="display: none;" id="row_<?=strtolower(str_replace(" ", "_", $bugdet_type));?>">
                    				<td colspan="14">
	                    				<table class="table table-bordered datatable ">
	                    					<thead>
	                    						<tr>
	                    							<th><?=get_phrase('field_office');?></th>
	                    							<?php
					                    				$months = range(1,12);
														
														foreach($months as $month){
					                    			?>
					                    					<th style="text-align: right;"><?=get_phrase('month').' '.$month;?></th>
					                    			<?php
														}
					                    			?>
					                    			<th style="text-align: right;"><?=get_phrase('year_total')?></th>
	                    						</tr>
	                    					</thead>
	                    					<tbody>
	                    		<?php
	                    				
	                    				foreach($field_offices as $field_office=>$month){
	                    		?>
	                    					<tr>
	                    						<td><?=$field_office;?></td>
	                    						<?php
	                    							$office_total = 0;
	                    							foreach($month as $amount){
	                    								$office_total += $amount;
	                    						?>
	                    							<td style="text-align: right;"><?=number_format($amount,2);?></td>
	                    						<?php
													}
	                    						?>
	                    						<td><?=number_format($office_total,2);?></td>
	                    					</tr>
	                    		<?php		
	                    				}
								?>
											</tbody>
										</table>
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
<style>
	.fa-eye{
		color:red;
	}
</style>
<script>	
	$(".show_hide_field_row").on('click',function(){
		var selector_id = $(this).attr('id');
			
		$(this).toggleClass('fa-eye-slash fa-eye');
		
		$("#row_"+selector_id).toggle("fast",function(){
			$(".field_office_row").each(function(){
				if($(this).attr('id')!== 'row_'+selector_id){
					$(this).css('display','none');
					
					var row_id = $(this).attr('id').replace('row_','');
					
					if($("#"+row_id).hasClass('fa-eye')){
						$("#"+row_id).toggleClass('fa-eye fa-eye-slash');
					}
					
				}
			});
		});
	});
</script>					