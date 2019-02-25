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
                    	<a href="<?=base_url();?>Budget/add_commitment_update" 
                    		class="<?=get_access('add_commitments_updates','view_active_commitments');?> 
                    		btn btn-default"><?=get_phrase('add_commitment');?> <i class="fa fa-reorder"> </i></a>
                    	
                    	<a href="<?=base_url();?>Budget/upload_monthly_update/commitment_update" 
                    		class="btn btn-default"><?=get_phrase('upload_commitments');?> 
                    		<i class="<?=get_access('add_commitments_updates','view_active_commitments');?> 
                    			fa fa-upload"></i></a>
                    	<hr />
                    	
                    	<table class="table table-striped table-responsive datatable">
                    		<thead>
                    			<th><?=get_phrase('office');?></th>
                    			<th><?=get_phrase('SOF_code');?></th>
                    			<th><?=get_phrase('SOF_name');?></th>
                    			<th><?=get_phrase('DEA_code');?></th>
                    			<th><?=get_phrase('commitment_date');?></th>
                    			<th><?=get_phrase('amount');?></th>
                    			<th><?=get_phrase('action');?></th>
                    		</thead>
                    		<tbody>
                    			<?php
                    				foreach($commitments as $commitment){
                    			?>
                    				<tr>
                    					<td><?=$commitment->office;?></td>
                    					<td><?=$commitment->sof_code;?></td>
                    					<td><?=$commitment->sof;?></td>
                    					<td><?=$commitment->dea_code;?></td>
                    					<td><?=$commitment->month;?></td>
                    					<td><?=number_format($commitment->amount,2)?></td>
                    					<td class="<?=get_access('show_commitment_update_action','view_commitment_update',0);?>">
                    						<div class="btn-group">
									                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
									                        <?php echo get_phrase('action');?> <span class="caret"></span>
									                    </button>
									                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
									                   		
									                   		<li class="<?=get_access('edit_commitment_update','show_commitment_update_action');?>">
									                   			<a class="" href="<?=base_url();?>budget/edit_commitment_update/<?=$commitment->commitment_id;?>">
									                               <i class="fa fa-pencil"></i>
									                               		<?php echo get_phrase('edit_commitment');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="<?=get_access('edit_commitment_update','show_commitment_update_action');?> divider"></li>
									                   		
									                   		<li class="<?=get_access('delete_commitment_update','show_commitment_update_action');?>">
																<a class="" id="perform_link" href="#" onclick="confirm_action('<?=base_url();?>Budget/delete_commitment_update/<?=$commitment->commitment_id;?>')">
									                               <i class="fa fa-trash"></i>
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
