<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('cleared_LPO');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	
                    	<div class="row <?=get_access('add_commitments_updates','view_active_commitments');?>">
                    		<div class="col-xs-12">
                    			<a href="<?=base_url();?>Budget/upload_monthly_update/commitment_update" 
		                    		class="btn btn-default"><?=get_phrase('upload_commitments');?> 
		                    		<i class="fa fa-upload"></i></a>
		                    		
		                    	<a href="<?=base_url();?>Budget/commitments_updates" 
		                    		class="btn btn-default"><?=get_phrase('active_commitments');?> 
		                    		<i class="fa fa-thumbs-down"></i></a>
		                    		
		                    	<a href="<?=base_url();?>Budget/delete_all_paid_commitments_updates" 
		                    		class="btn btn-default"><?=get_phrase('delete_paid_commitments');?> 
		                    		<i class="fa fa-thumbs-down"></i></a>	
		                    			
                    		</div>
                    	</div>
                    	<hr />
                    	<div class="row">
                    		<div class="col-sm-1">
                    			<a href="#" title="Previous Month List"><i style="font-size: 145pt;" class="fa fa-angle-left"></i></a>
                    		</div>
                    		
                    		<div class="col-sm-10">
                    			<table class="table table-striped table-responsive datatable">
		                    		<thead>
		                    			<th><?=get_phrase('L.P.O');?></th>
		                    			<th><?=get_phrase('commitment_date');?></th>
		                    			<th><?=get_phrase('amount');?></th>
		                    			<th><?=get_phrase('action');?></th>
		                    		</thead>
		                    		<tbody>
		                    			<?php
		                    				foreach($commitments as $commitment){
		                    			?>
		                    				<tr>
		                    					<td><?=$commitment->lpo;?></td>
		                    					<td><?=$commitment->month;?></td>
		                    					<td><?=number_format($commitment->amount,2);?></td>
		                    					<td class="<?=get_access('show_commitment_update_action','view_commitment_update',0);?>">
		                    						<div class="btn-group">
											                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
											                        <?php echo get_phrase('action');?> <span class="caret"></span>
											                    </button>
											                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
											                   		
											                   		<li class="<?=get_access('pay_commitment_item','show_commitment_update_action');?>">
																		<a class="" id="perform_link" href="#" onclick="showAjaxModal('<?=base_url();?>modal/popup/modal_pay_commitment/<?=$commitment->commitment_id;?>')">
											                               <i class="fa fa-thumbs-up"></i>
											                               		<?php echo get_phrase('undo_payment');?>
											                             </a>
											                   		</li>	
											                   		
											                   		<li class="divider <?=get_access('pay_commitment_item','show_commitment_update_action');?>"></li>
											                   		
											                   		<li class="<?=get_access('delete_commitment','show_commitment_update_action');?>">
																		<a class="" id="perform_link" href="#" onclick="confirm_action('<?=base_url();?>budget/delete_commitment/<?=$commitment->commitment_id;?>')">
											                               <i class="fa fa-times"></i>
											                               		<?php echo get_phrase('delete_commitment');?>
											                             </a>
											                   		</li>
											                   </ul>
											               </div>
		                    					</td>
		                    				</tr>
		                    			<?php
											}
		                    			?>
		                    			
		                    		</tbody>
		                    		
		                    	</table>
                    		</div>
                    		
                    		<div class="col-sm-1">
                    			<a href="#" title="Next Month List"><i style="font-size: 145pt;" class="fa fa-angle-right"></i></a>
                    		</div>
                    	</div>
                    	
                   </div>
                </div>
            </div>
       </div>
       
 <script>

</script>                   			
