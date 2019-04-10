<?php
//print_r($uploaded_data);
?>

<div class="row">
	<div class="col-xs-12">
		<a href="#" onclick="javascript:go_back();" class="btn btn-default"><?=get_phrase('show_listing');?> <i class="fa fa-reply"></i></a>
	</div>
</div>

<hr />

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
				?>
					<tr>
						<td>
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
							
								if(($cell_key == 2 || $cell_key == 3) && is_numeric($cell_value)){
									$cell_value = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell_value));
								}
								
						?>
							<td>
								<input type="text" value='<?=$cell_value;?>' name='<?=$uploaded_data[0][$column_count];?>[]' id='' class='form-control'/>
							</td>
						<?php $column_count++; endforeach;?>
					</tr>
				<?php
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					
					<td>
						<div id="btn_confirm" class="btn btn-default">Confirm <i class="fa fa-thumbs-up"></i></div>
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
		var url = '<?=base_url();?>budget/upload_reviewed_sof_data';
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			success:function(resp){
				$("#debug").show();
			}
		});
		
		ev.preventDefault();
	})
</script>