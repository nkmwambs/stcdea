<?php $current_date = isset($current_month)?$current_month:$month_epoch;?>

<style>

.table-fixed  {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td {
    border: 1px solid #FFA07A;
    text-align: left;
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
}

th:nth-child(-n+3), td:nth-child(-n+3)
{
  position:sticky;
  left:0px;
 
}
 td:nth-child(-n+3), td:nth-child(-n+3)
 {
  background-color:#ccc;
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
                    	
                       <div class="row">
		                    	
		                    	<div class="<?=get_access('add_bva_update','view_BVA_update');?> col-xs-2">
		                    		<a href="<?=base_url();?>Budget/upload_monthly_update/bva_update" 
			                    		class="btn btn-default"><?=get_phrase('upload_bva_updates');?> 
			                    		<i class="fa fa-upload"></i>
			                    	</a>
		                    	</div>
		                    	
		                    	<?php
		                    		if($month_epoch == $current_date){
		                    	?>
		                    	<div class="<?=get_access('delete_bva_update','view_BVA_update');?> col-xs-2">
		                    		<a href="<?=base_url();?>Budget/bva_updates/<?=$month_epoch;?>/delete" 
			                    		class="btn btn-default"><?=get_phrase('delete_bva_updates');?> 
			                    		<i class="fa fa-trash"></i>
			                    	</a>
		                    	</div>
		                    	
		                    	<?php
									}
		                    	?>
		                
	                   	</div> 			
                    	<hr />
                    	
                    	<div class="row">
							<div class="col-xs-12">
								<div class="btn btn-default scroll_month scroll_minus"><i class="fa fa-backward"></i></div>
								<div class="btn btn-default scroll_month current_month" id="current_month"><?=date('F Y',$month_epoch);?></div>
								<div class="btn btn-default scroll_month scroll_plus"><i class="fa fa-forward"></i></div>
								<input type="hidden" id="scroll_count" value="<?=isset($scroll_count)?$scroll_count:0;?>" />
								<div class="btn btn-default" id="scroll">Go</div>
								
								<div class="btn btn-default" id="reset">Reset to <?=date("F Y",$current_date);?></div>
							</div>
						</div>
						
						<hr/>
                    	
                    	<div class="row">
                    		<div class="col-sm-12">
								<table class="table table-striped table-fixed datatable">
			                    	<thead>
			                    		<tr>
			                    			<th rowspan="2"><?=get_phrase('office');?></th>
				                    		<th rowspan="2"><?=get_phrase('SOF_name');?></th>
				                    		<th rowspan="2"><?=get_phrase('DEA_description');?></th>
				                    		<!-- <th rowspan="2"><?=get_phrase('DEA_initial_amount');?></th> -->
				                    		<th colspan="<?=count($bva_updates[0]['month'])?>" style="text-align: center;"><?=get_phrase('month');?></th>
				                    		<th colspan="<?=count($bva_updates[0]['ytd'])?>" style="text-align: center;"><?=get_phrase('YTD');?></th>
				                    		<th colspan="<?=count($bva_updates[0]['full_year'])?>" style="text-align: center;"><?=get_phrase('full_year');?></th>
				                    		<th colspan="<?=count($bva_updates[0]['loa'])?>" style="text-align: center;"><?=get_phrase('life_of_award');?></th>
			                    		</tr>
			                    		
			                    		<tr>
			                    			<!--Month Headers-->
			                    			<th><?=get_phrase('forecast');?></th>
			                    			<th><?=get_phrase('actual');?></th>
			                    			<th><?=get_phrase('variance');?></th>
			                    			<th>% <?=get_phrase('variance');?></th>
			                    			
			                    			<!--YTD Headers-->
			                    			<th><?=get_phrase('forecast');?></th>
			                    			<th><?=get_phrase('actual');?></th>
			                    			<th><?=get_phrase('variance');?></th>
			                    			<th>% <?=get_phrase('variance');?></th>
			                    			
			                    			
			                    			<!--Full Year Headers-->
			                    			<th><?=get_phrase('forecast');?></th>
			                    			<th><?=get_phrase('year_remaining_balance');?></th>
			                    			<th><?=get_phrase('burn_rate');?></th>
			                    			
			                    			<!--LOA Headers-->
			                    			<th><?=get_phrase('forecast');?></th>
			                    			<th><?=get_phrase('actual');?></th>
			                    			<th>% <?=get_phrase('burn_rate');?></th>
			                    		</tr>
                    				</thead>
                    				<tbody>
                    					<?php
                   
                    						foreach($bva_updates as $update){
                    					?>
                    						<tr>
                    							<!--Description-->	
                    							<td><?=$update['dea_information']['office'];?></td>
                    							<td><?=$update['dea_information']['sof_code'].': '.$update['dea_information']['sof'];?></td>
                    							<td><?=$update['dea_information']['dea_code'].': '.$update['dea_information']['description'];?></td>
                    							
                    							
                    							<!--Month-->
                    							<td nowrap="nowrap"><?=number_format($update['month']['forecast'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['month']['actual'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['month']['variance'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format(($update['month']['per_variance']*100),2);?> %</td>
                    							
                    							<!--YTD-->
                    							<td nowrap="nowrap"><?=number_format($update['ytd']['forecast'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['ytd']['actual'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['ytd']['variance'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format(($update['ytd']['per_variance']*100),2);?> %</td>
                    							
                    							<!--Full Year-->
                    							<td nowrap="nowrap"><?=number_format($update['full_year']['forecast'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['full_year']['year_remaining_balance'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['full_year']['burn_rate']*100,2);?> %</td>
                    							
                    							<!--LOA-->
                    							<td nowrap="nowrap"><?=number_format($update['loa']['forecast'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format($update['loa']['actual'],2);?></td>
                    							<td nowrap="nowrap"><?=number_format(($update['loa']['burn_rate']*100),2);?> %</td>
                    							
                    						</tr>
                    					<?php
											}
                    					?>
                    				</tbody>
                    			</table>
							</div>
						</div>
				<!-- </div>
			</div> -->
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

		var url = "<?=base_url();?>budget/bva_updates_scroll/"+current_month+"/"+scroll_count;		
		//alert(scroll_count);
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


						