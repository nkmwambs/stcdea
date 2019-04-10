<div class="row">
	<div class="col-xs-12">
		<?php
			if(count($active_deas) == 0){
		?>
			<div class="well" style="color:red;">No records found</div>
		<?		
			}else{
		?>
			<a target="__blank" href='<?=base_url();?>budget/show_accounts_allocation/<?=$office_id;?>/<?=$start_date;?>' 
				class="btn btn-default"><?=get_phrase('download').' '.get_phrase($budget_type).' '
				.get_phrase('allocation_report');?></a>
				
			<!-- <a href='<?=base_url();?>budget/show_dea_summary/<?=$office_id;?>/<?=$start_date;?>' 
				class="btn btn-default"><?=get_phrase('DEA_summary_report');?></a> -->
		<?php
			}
		?>
	</div>
</div>
<hr />

<div class="row well">
	<div class="col-xs-12">

		<?php
			if(count($active_deas) == 0){
		?>
			<div class="well" style="color:red;">No active DEAs for this office</div>
		<?php		
			}
		?>
		<hr/>
		<a href='<?=base_url();?>budget/show_accounts_allocation/<?=$office_id;?>/<?=$start_date;?>/<?=$budget_type;?>' 
				class="btn btn-default"><?=get_phrase('download').' '.get_phrase($budget_type).' '
				.get_phrase('allocation_report');?></a>
				
			<!-- <a href='<?=base_url();?>budget/show_dea_summary/<?=$office_id;?>/<?=$start_date;?>' 
				class="btn btn-default"><?=get_phrase('DEA_summary_report');?></a> -->
		
		<hr/>
		
		<table class="table table-responsive">
			<thead>
				<tr>
					<th><?=get_phrase('Staff/Account');?></th>
					<th><?=get_phrase('Staff/Account_code')?></th>
					<th><?=get_phrase('DEA')?></th>
					<th><?=get_phrase('budget_ID')?></th>
					<th><?=get_phrase('year')?></th>
					<th><?=get_phrase('allocated_amount')?></th>
					<th><?=get_phrase('show_staff/Account_allocation')?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					
					foreach($records as $row){
						//$allocation = (array)$row->allocation;
				?>
					<tr>
						<td><?=$row->name;?></td>
						<?php
							$code_field = $table."_code";
						?>
						<td><?=$row->$code_field;?></td>
						<td class="td_dea_id">
							<?php
								$disabled = "";
								if($is_same_year == 0){
									$disabled = "disabled='disabled'";
								}
							?>
							<select class="form-control dea_id" name="dea_id[]" <?=$disabled;?> >
								<option value=""><?=get_phrase('select');?></option>
										<?php
											$amount = 0;
											foreach($active_deas as $key => $value){
										?>
											<optgroup label="<?=$key;?>">
												<?php
													
													foreach($value as $dea){
														$selected = "";
														
														foreach($row->allocation as $alloc_dea){
															if($alloc_dea->dea_id == $dea->dea_id ){
																$selected = "selected";
																$amount = $alloc_dea->amount;
															}
														}
												?>
													<option value="<?=$dea->dea_id;?>" <?=$selected;?> ><?=$dea->dea_code;?> [<?=$dea->office;?>]</option>
												<?php
													}
												?>
											</optgroup>
											
										<?php
											}
										?>	
							</select>
						</td>
						<td class="td_budget_id"><input type="text" class="form-control budget_id" readonly="readonly" 
							value="<?=$row->budget_id;?>" id="" name="budget_id[]" /></td>
						<td class="td_alloc_year"><input type="text" class="form-control alloc_year" readonly="readonly" 
							value="<?=date('Y',strtotime($row->start_date));?>" id="" name="alloc_year[]" /></td>
						<td class="td_amount"><input class="form-control amount" type="number" value="<?=isset($amount)?$amount:0;?>" name="amount[]"  readonly="readonly"/></td>
						<td><div class="btn btn-default" onclick="showAjaxModal('<?=base_url();?>modal/popup/show_account_allocation/<?=$row->budget_id;?>/<?=date('Y',strtotime($row->start_date));?>');">Show Allocation</div></td>
					</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
</div>

<script>

	$(".amount").on('dblclick',function(){
		var is_same_year = '<?=$is_same_year;?>';
		if(is_same_year == '0'){
			alert("Past financial period allocations can't be edited");
		}else{
			if($(this).closest('tr').find('.td_dea_id').find('select').val() !== ""){
				$(this).removeAttr('readonly');
			}else{
				alert('The value of DEA code is not set');
			}
		}
		//alert(is_same_year);
		
	});

	$(".dea_id,.amount").on('change',function(){
		
		var elem_budget = $(this).closest('tr').find('.td_budget_id').find('input');
		var elem_year = $(this).closest('tr').find('.td_alloc_year').find('input');
		var elem_amount = $(this).closest('tr').find('.td_amount').find('input');
		
		var budget_id = elem_budget.val();
		var year = elem_year.val();
		var amount = elem_amount.val();
		var dea_id = 0;
		var data = {};
		
		if($(this).hasClass('dea_id')){
			if($(this).closest('tr').find('.td_amount').find('input').val() != 0)
				$(this).closest('tr').find('.td_amount').find('input').val('0')
			
			if($(this).closest('tr').find('.td_amount').find('input').prop('readonly'))
				$(this).closest('tr').find('.td_amount').find('input').removeAttr('readonly');
				
			dea_id = $(this).val();
			
			//Check if allocation is already done
			var url = "<?=base_url();?>budget/check_dea_allocation/"+dea_id+"/"+budget_id+"/"+year;
			$.ajax({
				url:url,
				success:function(resp){
					//alert(resp.length);					
					elem_amount.val('0');
					
					if(resp.length > 2){
						var obj = JSON.parse(resp);
						elem_amount.val(obj[0].amount);
					} 	
				}
			});
			
			var data = {'dea_id':dea_id,'budget_id':budget_id,'alloc_year':year,'amount':JSON.parse(resp)[0].amount};
			
		}else{
			dea_id = $(this).closest('tr').find('.td_dea_id').find('select').val();
			var data = {'dea_id':dea_id,'budget_id':budget_id,'alloc_year':year,'amount':amount};
		}

		
		
		var url = '<?=base_url();?>budget/update_dea_allocation/<?=$budget_type;?>/<?=$start_date;?>'
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				
			},
			success:function(resp){
				//$(".office_budget_holder_<?=$office_id;?>").html(resp);
				
			},
			error:function(){
				
			}
		});
		
		
		
	});
	
</script>