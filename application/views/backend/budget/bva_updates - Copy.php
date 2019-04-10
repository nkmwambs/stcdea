<?php 

//print_r($bva_updates);

$current_date = isset($current_month)?$current_month:$month_epoch;

?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('BVA_updates');?> : <?=date('F Y',$month_epoch);?>
                        </div>
                    </div>
                    <div class="panel-body" style="overflow: auto;">
                    	
                       <div class="row">
							<div class="col-xs-12">                 	
		                    	<a href="<?=base_url();?>Budget/add_bva_update" 
		                    		class="<?=get_access('add_bva_update','view_BVA_update');?> 
		                    		btn btn-default"><?=get_phrase('add_bva_update');?> <i class="fa fa-reorder"> </i>
		                    	</a>
		                    	
		                    	<a href="<?=base_url();?>Budget/upload_monthly_update/bva_update" 
		                    		class="btn btn-default"><?=get_phrase('upload_bva_updates');?> 
		                    		<i class="<?=get_access('add_bva_update','view_BVA_update');?> 
		                    			fa fa-upload"></i>
		                    	</a>
		                    	<?php
		                    		if($month_epoch == $current_date){
		                    	?>
		                    	<a href="<?=base_url();?>Budget/bva_updates/<?=$month_epoch;?>/delete" 
		                    		class="btn btn-default"><?=get_phrase('delete_bva_updates');?> 
		                    		<i class="<?=get_access('delete_bva_update','view_BVA_update');?> 
		                    			fa fa-trash"></i>
		                    	</a>
		                    	<?php
									}
		                    	?>
		                    	
	                   		</div>
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
                    				<table class="table table-striped table-responsive table-bordered datatable">
			                    		<thead>
			                    			<th><?=get_phrase('office');?></th>
			                    			<!-- <th><?=get_phrase('SOF_code');?></th> -->
			                    			<th><?=get_phrase('SOF_name');?></th>
			                    			<!-- <th><?=get_phrase('DEA_code');?></th> -->
			                    			<th><?=get_phrase('DEA_description');?></th>
			                    			<th><?=get_phrase('DEA_initial_amount');?> (A)</th>
			                    			<th><?=get_phrase('month_actual');?> (B)</th>
			                    			<th><?=get_phrase('YTD_actual');?> (C)</th>
			                    			<th><?=get_phrase('life_of_award_actual');?> (D)</th>
			                    			<th><?=get_phrase('life_of_award_dea_balance');?> (E = A - D)</th>
			                    			<th><?=get_phrase('month_forecast');?></th>
			                    			<th><?=get_phrase('YTD_forecast');?></th>
			                    			<th><?=get_phrase('full_year_forecast');?></th>
			                    			<th><?=get_phrase('life_of_award_forecast');?></th>
			                    			<!-- <th><?=get_phrase('action');?></th> -->
			                    		</thead>
			                    		<tbody>
			                    			<?php
			                    				foreach($bva_updates as $update){
			                    			?>
				                    			<tr>
				                    				<td><?=$update->office;?></td>
				                    				<!-- <td><?=$update->sof_code;?></td> -->
				                    				<td title="<?=$update->sof;?>"><?=$update->sof_code.': '.$update->sof;?></td>
				                    				<!-- <td><?=$update->dea_code;?></td> -->
				                    				<td><?=$update->dea_code.': '.$update->description;?></td>
				                    				<td><?=number_format($update->initial_amount,2);?></td>
				                    				<td><?=number_format(($update->month_actual + $update->month_expense),2);?></td>
				                    				<td><?=number_format(($update->ytd_actual + $update->month_expense),2);?></td>
				                    				<td><?=number_format(($update->loa_actual + $update->month_expense),2);?></td>
				                    				<td><?=number_format(($update->initial_amount - ($update->loa_actual + $update->month_expense)),2);?></td>
				                    				<td><?=number_format($update->month_forecast,2);?></td>
				                    				<td><?=number_format($update->ytd_forecast,2);?></td>
				                    				<td><?=number_format($update->year_forecast,2);?></td>
				                    				<td><?=number_format($update->loa_forecast,2);?></td>
				                    				<!-- <td class="<?=get_access('show_bva_update_action','view_BVA_update',0);?>">
				                    						<div class="btn-group">
												                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
												                        <?php echo get_phrase('action');?> <span class="caret"></span>
												                    </button>
												                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
												                   		
												                   		<li class="<?=get_access('edit_bva_update','show_bva_update_action');?>">
												                   			<a class="" href="<?=base_url();?>budget/edit_bva_update/<?=$update->bva_update_id;?>">
												                               <i class="fa fa-pencil"></i>
												                               		<?php echo get_phrase('edit_update');?>
												                             </a>
												                   		</li>
												                   		
												                   		<li class="<?=get_access('edit_bva_update','show_bva_update_action');?> divider"></li>
												                   		
												                   		<li class="<?=get_access('delete_bva_update','show_bva_update_action');?>">
																			<a class="" href="#" onclick="confirm_action('<?=base_url();?>Budget/delete_bva_update/<?=$update->bva_update_id;?>',true)">
												                               <i class="fa fa-trash"></i>
												                               		<?php echo get_phrase('delete_update');?>
												                             </a>
												                   		</li>	
												                   </ul>
												               </div>
				                    				</td> -->
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
