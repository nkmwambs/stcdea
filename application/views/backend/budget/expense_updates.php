<<<<<<< HEAD
<?php
//echo $budget_type;
?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('expense_updates');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<!-- <a href="<?=base_url();?>Budget/add_expense_update" 
                    		class="<?=get_access('add_expense_update','view_expense_update');?> 
                    		btn btn-default"><?=get_phrase('add_expense');?> <i class="fa fa-reorder"> </i></a> -->
                    	<div class="row <?=get_access('add_expense_update','view_expense_update');?>">
                    		<div class="col-xs-12">
                    			<a href="<?=base_url();?>Budget/upload_monthly_update/expense_update" 
		                    		class="btn btn-default">
		                    			<?=get_phrase('upload_expenses');?> 
		                    		<i class="fa fa-upload"></i></a>
                    		</div>
                    	</div>
                    	
                    	<hr />
                    	
                    	<table class="table table-striped table-responsive datatable">
                    		<thead>
                    			<th><?=get_phrase('office');?></th>
                    			<th><?=get_phrase('SOF_code');?></th>
                    			<th><?=get_phrase('SOF_name');?></th>
                    			<th><?=get_phrase('DEA_code');?></th>
                    			<th><?=get_phrase('transaction_date');?></th>
                    			<th><?=get_phrase('amount');?></th>
                    			<th><?=get_phrase('action');?></th>
                    		</thead>
                    		<tbody>
                    			<?php
                    				foreach($month_expenses as $row){
                    			?>
                    				<tr>
                    					<td><?=$row->office;?></td>
                    					<td><?=$row->sof_code;?></td>
                    					<td><?=$row->sof;?></td>
                    					<td><?=$row->dea_code;?></td>
                    					<td><?=$row->month;?></td>
                    					<td><?=number_format($row->amount,2);?></td>
                    					<td class="<?=get_access('show_expense_update_action','view_expense_update',0);?>"> 
                    							<div class="btn-group">
									                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
									                        <?php echo get_phrase('action');?> <span class="caret"></span>
									                    </button>
									                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
									                   		
									                   		<li class="<?=get_access('edit_expense_update','show_expense_update_action');?>">
									                   			<a class="" href="<?=base_url();?>budget/edit_expense_update/<?=$row->expense_id;?>">
									                               <i class="fa fa-pencil"></i>
									                               		<?php echo get_phrase('edit_expense');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="<?=get_access('edit_expense_update','show_expense_update_action');?> divider"></li>
									                   		
									                   		<li class="<?=get_access('delete_expense_update','show_expense_update_action');?>">
									                   			<a class="" href="#" onclick="confirm_action('<?=base_url();?>Budget/delete_expense_update/<?=$row->expense_id;?>',true)">
									                               <i class="fa fa-trash"></i>
									                               		<?php echo get_phrase('delete_expense');?>
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
	$(".btn_delete_row").on('click',function(){
		$(this).closest('tr').remove();
	});

	$("#btn_confirm").on('click',function(ev){
		var frm = $("#frm_upload_data");
		var data = frm.serializeArray();
		var url = '<?=base_url();?>budget/upload_reviewed_monthly_update/<?=$budget_type;?>';
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				
			},
			success:function(resp){
				alert(resp);
			},
			error:function(){
				alert("Error Occurred");
			}
		});
		
		ev.preventDefault();
	})	
</script>		
=======
<?php
//echo $budget_type;
?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo get_phrase('expense_updates');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<!-- <a href="<?=base_url();?>Budget/add_expense_update" 
                    		class="<?=get_access('add_expense_update','view_expense_update');?> 
                    		btn btn-default"><?=get_phrase('add_expense');?> <i class="fa fa-reorder"> </i></a> -->
                    	<div class="row <?=get_access('add_expense_update','view_expense_update');?>">
                    		<div class="col-xs-12">
                    			<a href="<?=base_url();?>Budget/upload_monthly_update/expense_update" 
		                    		class="btn btn-default">
		                    			<?=get_phrase('upload_expenses');?> 
		                    		<i class="fa fa-upload"></i></a>
                    		</div>
                    	</div>
                    	
                    	<hr />
                    	
                    	<table class="table table-striped table-responsive datatable">
                    		<thead>
                    			<th><?=get_phrase('office');?></th>
                    			<th><?=get_phrase('SOF_code');?></th>
                    			<th><?=get_phrase('SOF_name');?></th>
                    			<th><?=get_phrase('DEA_code');?></th>
                    			<th><?=get_phrase('transaction_date');?></th>
                    			<th><?=get_phrase('amount');?></th>
                    			<th><?=get_phrase('action');?></th>
                    		</thead>
                    		<tbody>
                    			<?php
                    				foreach($month_expenses as $row){
                    			?>
                    				<tr>
                    					<td><?=$row->office;?></td>
                    					<td><?=$row->sof_code;?></td>
                    					<td><?=$row->sof;?></td>
                    					<td><?=$row->dea_code;?></td>
                    					<td><?=$row->month;?></td>
                    					<td><?=number_format($row->amount,2);?></td>
                    					<td class="<?=get_access('show_expense_update_action','view_expense_update',0);?>"> 
                    							<div class="btn-group">
									                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
									                        <?php echo get_phrase('action');?> <span class="caret"></span>
									                    </button>
									                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
									                   		
									                   		<li class="<?=get_access('edit_expense_update','show_expense_update_action');?>">
									                   			<a class="" href="<?=base_url();?>budget/edit_expense_update/<?=$row->expense_id;?>">
									                               <i class="fa fa-pencil"></i>
									                               		<?php echo get_phrase('edit_expense');?>
									                             </a>
									                   		</li>
									                   		
									                   		<li class="<?=get_access('edit_expense_update','show_expense_update_action');?> divider"></li>
									                   		
									                   		<li class="<?=get_access('delete_expense_update','show_expense_update_action');?>">
									                   			<a class="" href="#" onclick="confirm_action('<?=base_url();?>Budget/delete_expense_update/<?=$row->expense_id;?>',true)">
									                               <i class="fa fa-trash"></i>
									                               		<?php echo get_phrase('delete_expense');?>
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
	$(".btn_delete_row").on('click',function(){
		$(this).closest('tr').remove();
	});

	$("#btn_confirm").on('click',function(ev){
		var frm = $("#frm_upload_data");
		var data = frm.serializeArray();
		var url = '<?=base_url();?>budget/upload_reviewed_monthly_update/<?=$budget_type;?>';
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				
			},
			success:function(resp){
				alert(resp);
			},
			error:function(){
				alert("Error Occurred");
			}
		});
		
		ev.preventDefault();
	})	
</script>		
>>>>>>> 9e88b3b8f4be2c3aeccaabf46f10ce3dce528500
					