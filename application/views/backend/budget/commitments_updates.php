<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('commitments_tracker');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<!-- <a href="<?=base_url();?>Budget/add_commitment_update" 
                    		class="<?=get_access('add_commitments_updates','view_active_commitments');?> 
                    		btn btn-default"><?=get_phrase('add_commitment');?> <i class="fa fa-reorder"> </i></a> -->
                    	
                    	<div class="row <?=get_access('add_commitments_updates','view_active_commitments');?>">
                    		<div class="col-xs-12">
                    			<a href="<?=base_url();?>Budget/upload_monthly_update/commitment_update" 
		                    		class="btn btn-default"><?=get_phrase('upload_commitments');?> 
		                    		<i class="fa fa-upload"></i></a>
		                    		
		                    	<a href="<?=base_url();?>Budget/cleared_lpo" 
		                    		class="btn btn-default"><?=get_phrase('cleared_LPO');?> 
		                    		<i class="fa fa-thumbs-up"></i></a>
		                    			
                    		</div>
                    	</div>
                    	<hr />
                    	
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
									                   		
									                   		<li class="<?=get_access('pay_commitment_single_item','show_commitment_update_action');?>">
																<a class="" id="perform_link" href="#" onclick="showAjaxModal('<?=base_url();?>modal/popup/modal_pay_commitment/<?=$commitment->commitment_id;?>')">
									                               <i class="fa fa-thumbs-up"></i>
									                               		<?php echo get_phrase('pay_an_item');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="divider"></li>
									                   		
									                   		<li class="<?=get_access('pay_commitment_full_lpo','show_commitment_update_action');?>">
																<a class="" id="perform_link" href="#" onclick="confirm_modal('<?=base_url();?>budget/pay_full_commitment/<?=$commitment->commitment_id;?>')">
									                               <i class="fa fa-money"></i>
									                               		<?php echo get_phrase('pay_full_commitment');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="divider"></li>
									                   		
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
                </div>
            </div>
       </div>
       
 <script>

</script>                   			
