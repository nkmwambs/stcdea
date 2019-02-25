<?php
//print_r($records);
//echo date("Y-m-d",$current_month);
//echo date('Y-m-d',$current_month);
//echo $selected_date;
?>

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

<hr />

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
                    	<?php 
							echo form_open(base_url() . 'budget/dea_absorption_report/'.date('Y-m-d',$current_month), array('id'=>'frm_burn_rate_type', 'class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
						?>
							<div class="form-group">
								<label class="control-label col-sm-4">Time frame</label>
								<div class="col-sm-6">
									<select class="form-control" name="timeline" id="timeline">
										<option value="ytd"><?=get_phrase('select');?></option>
										<option value="ytd" <?php if($timeline == 'ytd') echo 'selected';?> ><?=get_phrase('year_to_date');?></option>
										<option value="full_year" <?php if($timeline == 'full_year') echo 'selected';?> ><?=get_phrase('full_year');?></option>
										<option value="loa" <?php if($timeline == 'loa') echo 'selected';?> ><?=get_phrase('life_of_award');?></option>
									</select>
								</div>
								<div class="col-sm-2">
									<button type="submit" class="btn btn-default"><?=get_phrase('go');?></button>
								</div>
							</div>
						</form>
                    	<hr />
                    	<?php
                    		if(count($records) == 0){
                    	?>
                    		<div class="well"><?=date("F Y",$current_date);?> <?=get_phrase('BVA_update_not_done');?></div>
                    	<?php		
                    		}else{
                    	?>
                    	<table class="table table-striped datatable">
                    		<thead>
                    			<tr>
                    				<th>SOF Code</th>
                    				<th>SOF Name</th>
                    				<th>DEA Code</th>
                    				<th>DEA Description</th>
                    				<th>Office</th>
                    				<th>Actual (A)</th>
                    				<th><?=get_phrase($timeline);?> Forecast (B)</th>
                    				<th>Month Expense Update (C)</th>
                    				<th>Expense To Date (D = A + C)</th>
                    				<th>Allocation (E)</th>
                    				<th>Unabsorbed Allocation (F = E - D)</th>
                    				<th>% Burn Rate (G = (D/B)*100)</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    			<?php 
                    				foreach($records as $row){
                    			?>
                    				<tr>
                    					<td><?=$row['sof_code'];?></td>
                    					<td><?=$row['sof'];?></td>
                    					<td><?=$row['dea_code'];?></td>
                    					<td><?=$row['description'];?></td>
                    					<td><?=$row['office'];?></td>
                    					<td><?=number_format($row['month_actual'],2)?></td>
                    					<td><?=number_format($row['forecast'],2);?></td>
                    					<td><?=number_format($row['expense'],2);?></td>
                    					<?php
                    						$forecast = $row['forecast'];
                    						$allocation = isset($row['amount'])?$row['amount']:0;
											$expense = isset($row['expense'])?$row['expense']:0;
											$burn_rate = ($forecast == 0?1:(($row['month_actual'] + $expense)/$forecast))*100;
                    						$unabsd_alloc = $allocation - ($row['month_actual'] + $expense);
                    					?>
                    					<td><?=number_format(($row['month_actual'] + $expense),2);?></td>
                    					<td><?=number_format($row['amount'],2);?></td>
                    					<td><?=number_format($unabsd_alloc,2);?></td>
                    					<td><?=number_format($burn_rate,2);?>%</td>
                    				</tr>
                    			<?php 
									}
                    			?>
                    		</tbody>
                    	</table>
                    	<?php
                    	}
                    	?>
					</div>
			</div>
	</div>
</div>	

<script>

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
		
		var url = "<?=base_url();?>budget/dea_absorption_report_scroll/"+current_month+"/"+scroll_count;		
		
		
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
</script>				