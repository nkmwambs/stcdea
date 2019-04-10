<?php
if($this->session->login_user_id == 1){
	//print_r($bva_update);
	//echo $latest_bva_update;
}
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

thead th:first-child,tbody td:first-child, tfoot td:first-child
{
  position:sticky;
  left:0px;
 
}
 tbody td:first-child
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
	<div class="col-sm-12 ">
		<span class="" style="font-weight: bold;">Office: </span><?=$this->db->get_where('office',array('office_id'=>$office_id))->row()->name;?>
	</div>
	<div class="col-sm-12 ">
		<span class="" style="font-weight: bold;">Latest BVA Update: </span><?=date('jS F Y',strtotime($latest_bva_update));;?>
		
	</div>
</div>

<hr/>

<?php 
	//print_r($active_deas);
	if(count($active_deas) == 0){
?>	

	<div class="row">
		<div class="col-xs-12">
			<div class="label label-primary">There are no active DEAs for this office and budget type</div>
		</div>
	</div>
	<hr />
<?php
	}
?>

<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-fixed <?php if(count($active_deas) > 0) echo "datatable";?>">
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
						<td nowrap="nowrap" class="accounting" id="budgetamount_<?=$account->budget_id;?>"><?=$account->amount;?></td>
						<?php
							$allocation = !empty($account->allocation)?(array)$account->allocation:array();
							$sum_allocated = array_sum($allocation);
							$gap = $account->amount -$sum_allocated;
						?>
						
							<td class="accounting" id="allocation_<?=$account->budget_id;?>"><?=$sum_allocated;?></td>
							
							<td class="accounting" nowrap="nowrap" id="fundinggap_<?=$account->budget_id;?>" style="border-right:solid black 1px;"><?=$gap;?></td>	
						
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
											
											<input type="number" class="form-control allocate_input inputdea_<?=$dea->dea_id;?> inputcostcenter_<?=$account->budget_id;?>" 
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
					//$budget_item = get_phrase('all')." ".ucwords(str_replace("_", " ",$table)).' '.get_phrase('cost').' '.get_phrase('DEA_allocation');
					$total_row_titles = array('ytd_allocations'=>"Forecast DEA Allocation (A)",'loa_forecast'=>'LOA Forecast (B)','initial_loa_actuals'=>'Initial LOA Actuals b/f (C)','loa_actuals'=>'LOA Actual (D)','loa_dea_balance'=>'LOA DEA Balance (E = B - (C+D))');
										
					$row_spread_value = 0;
					foreach($total_row_titles as $key=>$row){
				?>
					<tr>
						<td nowrap="nowrap" style="border-right:solid black 1px;"><?=$row;?></td>
						<td colspan="4"></td>
						<?php
						
							foreach($active_deas as $sof=>$deas){
									
								foreach($deas as $dea){
									
									$amount = 0;
									
									if($i == count($deas)) $style = "border-right:solid black 1px;";
									$amount = isset($bva_update[$key][$dea->dea_id])?$bva_update[$key][$dea->dea_id]:0;
									
						?>
									<td style='<?=$style?> min-width:120px;' class='accounting' id='<?=$key;?>_<?=$dea->dea_id?>'><?=$amount;?></td>
									
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
	$(document).ready(function(){
		$(".accounting").each(function(i,el){
			var formated = accounting.formatMoney($(this).html(), { symbol: "",  format: "%v %s" });	
			var dea_allocation_total = 0;
			
			//Format all values of tds with accounting class to currency format
			$(this).html(formated);
			
		});
	});
	
	var inputdea_amount = 0;
	
	$('.allocate_input').on('click',function(){
		inputdea_amount = $(this).val();
	});
		
	$('.allocate_input').on('change',function(){
		var id = $(this).attr('id');
		var id_obj = id.split("_");
		var dea_id = id_obj[1];
		var budget_id = id_obj[0];
		var alloc_year = id_obj[2];
		var amount = $(this).val();
		var allocation = $("#allocation_"+budget_id).html();
		var budgetamount = $("#budgetamount_"+budget_id).html();
		var fundinggap = $("#fundinggap_"+budget_id).html();
		var ytdallocation = $("#ytd_allocations_"+dea_id).html();
		var loadeabalance = $("#loa_dea_balance_"+dea_id).html();
		
		//Calculate Ytd allocation on change - This should the first check
		var dea_allocation_total = 0;
		$(".inputdea_"+dea_id).each(function(i,el){
			dea_allocation_total += parseFloat($(el).val());
		});
		
		var cost_center_allocation_total = 0;
		$(".inputcostcenter_"+budget_id).each(function(i,el){
			cost_center_allocation_total += parseFloat($(el).val());
		});
		
			
		/**Check if YTD Allocations exceed LOA DEA Balance when changing the allocation cell value
		 * Return false  if exceeds - Always should be second check
		 *  **/
		ytd_allocation_and_loa_dea_balance_difference = parseFloat(accounting.unformat($("#loa_dea_balance_"+dea_id).html())) - parseFloat(dea_allocation_total);	
		
		if(ytd_allocation_and_loa_dea_balance_difference < 0){
			alert('You have exceeded the LOA DEA Balance of '+ $("#loa_dea_balance_"+dea_id).html()+" by " + ytd_allocation_and_loa_dea_balance_difference);
			$(this).val(inputdea_amount);
			
			return false;
		}
		
		fundinggap = parseFloat(accounting.unformat($("#budgetamount_"+budget_id).html())) - parseFloat(cost_center_allocation_total);
		
		if(fundinggap < 0){
			alert('You have exceeded the Funding gap of '+ $("#budgetamount_"+budget_id).html()+" by " + fundinggap);
			$(this).val(inputdea_amount);
			
			return false;
		}
		
		
		/**SPAN holding value tobe used when printing the sheet to excel**/
		$('#spandea_'+budget_id+'_'+dea_id+'_'+alloc_year).html($(this).val());
		
		/**Compute allocation and funding gap when the this value changes**/
		allocation_formatted = accounting.formatMoney(cost_center_allocation_total, { symbol: "",  format: "%v %s" });// Format %v = value, %s = currency symbol
		$("#allocation_"+budget_id).html(allocation_formatted);
		
		
		fundinggap = parseFloat(accounting.unformat(budgetamount)) - parseFloat(accounting.unformat(cost_center_allocation_total)); 
		fundinggap_formatted = accounting.formatMoney(fundinggap, { symbol: "",  format: "%v %s" });
		$("#fundinggap_"+budget_id).html(fundinggap_formatted);
		
		
		formated_dea_allocation_total = accounting.formatMoney(dea_allocation_total, { symbol: "",  format: "%v %s" });
		ytdallocation = $("#ytd_allocations_"+dea_id).html(formated_dea_allocation_total);
			
		
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