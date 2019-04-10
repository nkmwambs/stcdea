<?php
//print_r($uploaded_data);
//echo date('n');
?>
<style>
	input{
		min-width: 100px;
	}
</style>
<div class="row">
	<div class="col-xs-12">
		<a href="#" onclick="javascript:go_back();" class="btn btn-default"><?=get_phrase('show_listing');?> <i class="fa fa-reply"></i></a>
	</div>
</div>

<hr />

<h4><?=get_phrase($budget_type);?></h4>

<div class="row">
	<div class="col-xs-12">
		<table class="table table-responsive">
			<?php echo form_open("", array('id' => 'frm_upload_data', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
			<thead>
				<tr>
					<th><?=get_phrase('action')?></th>
					<?php foreach($uploaded_data[0] as $cell_header_value):?>
						<th><?=get_phrase($cell_header_value);?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>
				<?php
					$row_count = 0; 
					foreach($uploaded_data as $key=>$value){
						if($key == 0) continue;
						
						if($value[0] !== ""){
				?>
					<tr>
						<td nowrap="nowrap">
							<div class="btn-group left-dropdown">
								<a class="btn btn-default" href="#"><?=get_phrase('action');?></a>
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="#" class="btn_delete_row">
											<i class="fa fa-trash"></i>
												<?=get_phrase('delete')?>
										</a>
									</li>
									<li class="divider"></li>
								</ul>
							</div>		
						</td>
					
						<?php 
							$column_count = 0; foreach($value as $cell_key=>$cell_value):
							if(count($value) == 18){// Staff Cost
								if($cell_key == 4 || $cell_key == 5){
									$cell_value = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell_value));
								}
							}else{//Thematic/ Non Thematic == 19
								if($cell_key == 5 || $cell_key == 6){
									if(is_numeric($cell_value)){
										$cell_value = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell_value));	
									}
									
								}
							}
								
						?>
							<td>
								<input type="text" value='<?=$cell_value;?>' name='<?=$uploaded_data[0][$column_count];?>[]' id='' class='form-control'/>
							</td>
						<?php $column_count++; endforeach;?>
					</tr>
				<?php
						$row_count ++;
					}	
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					
					<td colspan="17">
						<div id="btn_confirm" class="btn btn-default">Confirm (Uploading Count: <?=$row_count;?> records) <i class="fa fa-thumbs-up"></i></div>
					</td>
				</tr>
			</tfoot>
		</table>
		</form>
	</div>
</div>

<script>
	
	$(".btn_delete_row").on('click',function(){
		$(this).closest('tr').remove();
	});

	$("#btn_confirm").on('click',function(ev){
		var frm = $("#frm_upload_data");
		var data = frm.serializeArray();
		var url = '<?=base_url();?>budget/upload_reviewed_data/<?=$budget_type;?>';
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