<?php
//print_r($bva_update);
?>
<style>

.table-fixed  {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

.table-fixed thead th 
{
	position: sticky;
    position: -webkit-sticky;
    top: 0;
    z-index: 999;
    background-color: #000;
    color: #fff;
}

thead th:nth-child(-n+5),tbody td:nth-child(-n+5)
{
  position:sticky;
  left:0px;
 
}
 tbody td:nth-child(-n+5)
 {
  background-color:gray;
 }
 
 tfoot td:first-child
 {
  background-color:gray;
 }
 
 
 
/**Freeze top row**/
.table-fixed thead {
  position: sticky;
  position: -webkit-sticky;
  top: 0;
  z-index: 999;
  background-color: #000;
  color: #fff;
}


</style>

<div class="row">
	<div class="col-sm-12">
		<span style="font-weight: bold;">Period Start Date: </span><?=date('jS F Y',$first_day_of_the_year_epoch);?>
	</div>
</div>

<hr/>

<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-fixed datatable">
			<thead>
				<tr>
					<th nowrap="nowrap" rowspan="3"><?=ucwords(str_replace("_", " ",$table));?> Name</th>
					<th nowrap="nowrap" rowspan="3"><?=ucwords(str_replace("_", " ",$table));?> Code</th>
					<th nowrap="nowrap" rowspan="3"><?=get_phrase('total_budget_per')." ".ucfirst($table);?></th>
					<th nowrap="nowrap" rowspan="3"><?=ucfirst($table).' '.get_phrase('total_allocation');?></th>
					<th nowrap="nowrap" rowspan="3"><?=ucfirst($table).' '.get_phrase('budget_gap');?></th>
				</tr>
				<tr>
					<?php
						foreach($active_deas as $sof=>$deas){
					?>
						<th colspan="<?=count($deas);?>" style="border-right:solid white 1px;"><?=$sof;?></th>
					<?php
						}
					?>
				</tr>
				
				<tr>
					<?php
						
						foreach($active_deas as $sof=>$deas){
							//$i = 1;
							$style = "";
							
							foreach($deas as $dea){
								//if($i == count($deas)) $style = "style='border-right:solid black 1px;'";
					?>
								<th <?=$style?> ><?=$dea->dea_code.': '.$dea->description;?></th>
					<?php
								//$i++;
							}
						}
					?>

				</tr>
			</thead>
			<tbody>
				<?php
					foreach($records as $account){
						$code = $table.'_code';
				?>
					<tr>
						<td nowrap="nowrap"><?=$account->name;?></td>
						<td nowrap="nowrap" style="border-right:solid black 1px;"><?=$account->$code;?></td>
						<td nowrap="nowrap"><?=number_format($account->amount,2);?></td>
						<?php
							$allocation = !empty($account->allocation)?(array)$account->allocation:array();
							$sum_allocated = array_sum($allocation);
							$gap = $account->amount -$sum_allocated;
						?>
						
							<td><?=number_format($sum_allocated,2);?></td>
							
							<td nowrap="nowrap" style="border-right:solid black 1px;"><?=number_format($gap,2);?></td>	
						
						<?php
						
								foreach($active_deas as $sof=>$deas){
									$i = 1;
									$style = "";
									
									foreach($deas as $dea){
										if($i == count($deas)) $style = "border-right:solid black 1px;";
							?>
										<td style='<?=$style?> min-width:120px;' class="allocate">
											
											<?php
												
												$allocated_amount = 0;
												if(isset($account->allocation)){
													foreach($account->allocation as $dea_id=>$alloc_amount){
													if($dea->dea_id == $dea_id ){
														$allocated_amount = $alloc_amount;
													}
												}
												}
												
											?>
											
											<input type="number" class="form-control allocate_input inputdea_<?=$dea->dea_id;?>" 
												id="<?=$account->budget_id.'_'.$dea->dea_id.'_'.date('Y',strtotime($start_date));?>" 
												name="" value="<?=$allocated_amount;?>" />
												<span style="display: none;" id="spandea_<?=$account->budget_id.'_'.$dea->dea_id.'_'.date('Y',strtotime($start_date));?>"><?=number_format($allocated_amount,2);?></span>
										
										</td>
							<?php
										$i++;
									}
								}
							?>
						
					</tr>
				<?php
					}
				?>
				
			</tbody>
			
			<tfoot>
				
				<?php
					$budget_item = get_phrase('all')." ".ucwords(str_replace("_", " ",$table)).' '.get_phrase('cost').' '.get_phrase('DEA_allocation');
					$total_row_titles = array('budget_item'=>$budget_item,'loa_forecast'=>'LOA Forecast','loa_actual'=>'LOA Actual','loa_dea_balance'=>'LOA DEA Balance');
					
					$dea_ids_array = array_column($bva_update, 'dea_id');
					$loa_actual_array = array_column($bva_update, 'loa_actual');
					$loa_forecast_array = array_column($bva_update, 'loa_forecast');
					//$ytd_forecast_array = array_column($bva_update, 'ytd_forecast');
						
					$loa_actual_with_dea_id_keys = array_combine($dea_ids_array, $loa_actual_array);
					$loa_forecast_with_dea_id_keys = array_combine($dea_ids_array, $loa_forecast_array);
					//$ytd_forecast_with_dea_id_keys = array_combine($dea_ids_array, $ytd_forecast_array);
										
					$row_spread_value = 0;
					foreach($total_row_titles as $key=>$row){
				?>
					<tr>
						<td colspan="5" style="border-right:solid black 1px;"><?=$row;?></td>
						<?php
						
							foreach($active_deas as $sof=>$deas){
								$i = 1;
								$style = "";
									
								foreach($deas as $dea){
									if($i == count($deas)) $style = "border-right:solid black 1px;";
									
									if($key == 'budget_item'){
										$row_spread_value = array_sum(array_column(array_column($records, 'allocation'),$dea->dea_id));
									}elseif($key == 'loa_forecast'){
										$row_spread_value = $loa_forecast_with_dea_id_keys[$dea->dea_id];
									}elseif($key == 'loa_actual'){
										$row_spread_value = $loa_actual_with_dea_id_keys[$dea->dea_id];
									}elseif($key == 'loa_dea_balance'){
										$row_spread_value = $loa_forecast_with_dea_id_keys[$dea->dea_id] - $loa_actual_with_dea_id_keys[$dea->dea_id];
									}elseif($key == 'ytd_forecast'){
										$row_spread_value = $ytd_forecast_with_dea_id_keys[$dea->dea_id];;
									}
						?>
									<td style='<?=$style?> min-width:120px;' class='<?=$key;?>' id='<?=$key.'_'.$dea->dea_id;?>'><?=number_format($row_spread_value,2);?></td>
						<?php
									$i++;
								}
							}
						?>
					</tr>
				<?php
						
					}
				?>
				
			</tfoot>
		</table>
	</div>
</div>

<script>
	
	var old_budget_item_value = 0;
	var old_budget_total_value = 0;
	var id = 0;
	var dea_id = 0;
	var budget_id = 0;
	var alloc_year = 0;
	
	
	$('.allocate_input').click(function(){
		id = $(this).attr('id');
		id_obj = id.split("_");
		dea_id = id_obj[1];
		budget_id = id_obj[0];
		alloc_year = id_obj[2];
		
		
		old_budget_item_value = $(this).val();
		old_budget_total_value = parseFloat($('#budget_item_'+dea_id).html().replace(/,/g,''));
		//alert(old_budget_total_value);
	}); 

	// $('.allocate_input').on('keyup',function(){
		// $('#spandea_'+budget_id+'_'+dea_id+'_'+alloc_year).html($(this).val());
	// });
		
	$('.allocate_input').on('change',function(){
		var amount = $(this).val();
		var dea_budget_total = parseFloat($('#budget_item_'+dea_id).html().replace(/,/g,''));
		var dea_balance = parseFloat($('#loa_dea_balance_'+dea_id).html().replace(/,/g,''));
		
		$('#spandea_'+budget_id+'_'+dea_id+'_'+alloc_year).html($(this).val());
		
		//Compute totals
		var dea_budget_total = 0.00;
		
		$(".inputdea_"+dea_id).each(function(i,el){
			dea_budget_total += parseFloat($(el).val());
		});
		
		$('#budget_item_'+dea_id).html(dea_budget_total.toLocaleString()+'.00');
		
		if(dea_budget_total > dea_balance){
			alert('You have exceeded the available amount');
			$('#budget_item_'+dea_id).html(old_budget_total_value);

		}
		
		//Post with ajax
		var data = {'budget_id':budget_id,'dea_id':dea_id,'alloc_year':alloc_year,'amount':amount};
		var url = '<?=base_url();?>budget/update_dea_from_spread'
		
		$.ajax({
			url:url,
			data:data,
			type:'POST',
			success:function(resp){
				//alert(resp);
			},
			error:function(){
				alert('Error Occurred!');
			}
		});
		
	});
</script>