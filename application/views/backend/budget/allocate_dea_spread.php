<?php
if($this->session->login_user_id == 1){
	//print_r($bva_update);
	//echo $latest_bva_update;
}
?>
<style>

.rotate {
  transform: rotate(-90deg);
  transform-origin: top left;
}

.form-control{
	max-width: 100px;
	text-align: right;
}

.pull_left{
	text-align:left;
}

.table-fixed  {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td {
    border: 1px solid #FFA07A;
    text-align: right;
    padding: 8px;
}

.table-fixed thead th 
{
	position: sticky;
    position: -webkit-sticky;
    top: 0;
    z-index: 999;
    background-color: #FFA07A;
    color: #fff;
    border: 1px solid #ccc;
    height: 75px;
    width: 50px;
    text-align:left;
}

thead th:first-child,tbody td:first-child, tfoot td:first-child
{
  position:sticky;
  left:0px;
 
}
 tbody td:first-child
 {
  background-color:#ccc;
 }
 
 tfoot td:first-child
 {
  background-color:#FFA07A;
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
					<th nowrap="nowrap" rowspan="3"><div class="rotate"><?=ucwords(str_replace("_", " ",$table));?> Code</div></th>
					<th nowrap="nowrap" rowspan="3"><div class="rotate"><?=get_phrase('total_budget_per')." ".ucfirst($table);?></div></th>
					<th nowrap="nowrap" rowspan="3"><div class="rotate"><?=ucfirst($table).' '.get_phrase('total_allocation');?></div></th>
					<th nowrap="nowrap" rowspan="3"><div class="rotate"><?=ucfirst($table).' '.get_phrase('budget_gap');?></div></th>
					
				</tr>
				<tr>
					<?php
						foreach($active_deas as $sof=>$deas){
					?>
						<th colspan="<?=count($deas);?>" title="<?=$sof;?>" style="border-right:solid white 1px;"><?=substr($sof,0,50);?></th>
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
					?>
								<th  <?=$style?> title="<?=$dea->description;?>"><?=$dea->dea_code.': '.substr($dea->description,0,50);?></th>
					<?php

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
						<td nowrap="nowrap" class="pull_left"><?=$account->name;?></td>
						<td nowrap="nowrap" class="" style="border-right:solid black 1px;"><?=$account->$code;?></td>
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
					//print_r($bva_update);
					
					// $total_row_titles = array('ytd_allocations'=>"Forecast DEA Allocation (A)",'year_forecast'=>'Year Forecast (B)','initial_loa_actuals'=>'Initial LOA Actuals b/f (C)','loa_actuals'=>'LOA Actual (D)','loa_dea_balance'=>'LOA DEA Balance (E = B - (C+D))');
					// $total_row_titles = array('ytd_allocations'=>"Forecast DEA Allocation (A)",'year_forecast'=>'Full Year Forecast (B)','ytd_actuals'=>'YTD Actual (C)','expenses'=>'Month Expenses (D)','commitments'=>'Month Commitments (E)','year_remaining_balance'=>'Year Remaining Balance','year_allocation_balance'=>'Available for allocation');					
					$total_row_titles = array('ytd_allocations'=>"Forecast DEA Allocation (A)",'expenses'=>'Month Expenses (D)','commitments'=>'Month Commitments (E)','year_remaining_balance'=>'Year Remaining Balance','year_allocation_balance'=>'Available for allocation');					
					$row_spread_value = 0;
					foreach($total_row_titles as $key=>$row){
				?>
					<tr>
						<td nowrap="nowrap" class="pull_left" style="border-right:solid black 1px;"><?=$row;?></td>
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
			//var formated = accounting.formatMoney($(this).html(), { symbol: "",  format: "%v %s" });	
			//var dea_allocation_total = 0;
			
			//Format all values of tds with accounting class to currency format
			//$(this).html(formated);
			
		});
	});
	
	var inputdea_amount = 0;
	
	$('.allocate_input').on('click',function(){
		inputdea_amount = $(this).val();
		//alert(inputdea_amount);
	});
	
	$('.allocate_input').on('change',function(){
		var id = $(this).attr('id');
		var id_obj = id.split("_");
		var dea_id = id_obj[1];
		var budget_id = id_obj[0];
		var alloc_year = id_obj[2];
		var amount = $(this).val();
		var cost_center_allocation = $("#allocation_"+budget_id).html();
		var budgetamount = $("#budgetamount_"+budget_id).html();
		var fundinggap = $("#fundinggap_"+budget_id).html();
		var ytdallocation = $("#ytd_allocations_"+dea_id).html();
		var year_allocation_balance = $("#year_allocation_balance_"+dea_id).html();
		var year_forecast_balance = $("#year_forecast_balance_"+dea_id).html();
		var year_remaining_balance = $("#year_remaining_balance_"+dea_id).html();
		var expenses = $("#expenses_"+dea_id).html();
		var commitments = $("#commitments_"+dea_id).html();
		
		var prev_value = inputdea_amount;
		
		//alert(inputdea_amount);
		
		dea_allocation_total = total_dea_allocation(dea_id);
		
		computed_cost_center_allocation_total = total_cost_center_allocation(budget_id);
		
		//Gap and balance computation
		
		computed_year_allocation_balance = parseFloat(year_remaining_balance) - (parseFloat(expenses) + parseFloat(commitments) + parseFloat(dea_allocation_total));
		
		computed_fundinggap = parseFloat($("#budgetamount_"+budget_id).html()) - parseFloat(computed_cost_center_allocation_total);
		 
		
		if(computed_year_allocation_balance < 0 || computed_fundinggap < 0){
			alert("You have exceed the maximum possible amount that you can allocate or have exceed the maximum possible budget. Your changed value was not saved");
			$(this).val(prev_value);			
		}else{
			//Assign values dynamically
		
			$("#year_allocation_balance_"+dea_id).html(computed_year_allocation_balance);
			
			$("#ytd_allocations_"+dea_id).html(dea_allocation_total);
			
			$("#allocation_"+budget_id).html(computed_cost_center_allocation_total);
			
			$("#fundinggap_"+budget_id).html(fundinggap_value(budget_id));
			
				
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
		}
		
			
		
	});
	
	function fundinggap_value(budget_id){
		
		var cost_center_allocation_total = total_cost_center_allocation(budget_id);
		
		var fundinggap = parseFloat($("#budgetamount_"+budget_id).html()) - parseFloat(cost_center_allocation_total);
		
		return fundinggap;
		
	}
	
	function ytd_allocation_and_loa_dea_balance_difference(elem,dea_id){
			/**Check if YTD Allocations exceed LOA DEA Balance when changing the allocation cell value
			 * Return false  if exceeds - Always should be second check
			 *  **/
			
			var total_dea_allocation = total_dea_allocation(dea_id);
			
			return ytd_allocation_and_loa_dea_balance_difference;
	}
	
	function total_cost_center_allocation(budget_id){
			
			var cost_center_allocation_total = 0;
			
			$(".inputcostcenter_"+budget_id).each(function(i,el){
				cost_center_allocation_total = parseFloat(cost_center_allocation_total) + parseFloat($(el).val());
			});
			//alert(cost_center_allocation_total);
			return cost_center_allocation_total;
		}
	
	
	function total_dea_allocation(dea_id){
			//Calculate Ytd allocation on change - This should the first check
			var dea_allocation_total = 0;
			$(".inputdea_"+dea_id).each(function(i,el){
				dea_allocation_total += parseFloat($(el).val());
			});	
			
		return dea_allocation_total;	
	}
</script>