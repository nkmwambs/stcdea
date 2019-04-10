<?php
//print_r($active_deas);
?>
<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo get_phrase('add_expense_update');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<a href="#" onclick="javascript:go_back();" class="btn btn-default">Show Listing</a>
                    	
                    	<hr/>
						
						<?php 
							echo form_open(base_url() . 'budget/insert_expense_update/', array('id'=>'frm_add_expense_update', 'class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
						
						?>
						
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('DEA_code');?></label>
								<div class="col-xs-8">
									<select class="form-control select2" name="dea_id" id="dea_id">
										<option value=""><?=get_phrase('select');?></option>
										<?php
											foreach($active_deas as $key => $row){
										?>
											<optgroup label="<?=$key;?>">
												<?php
													foreach($row as $dea){
												?>
													<option value="<?=$dea->dea_id;?>"><?=$dea->dea_code;?> [<?=$dea->office;?>]</option>
												<?php
													}
												?>
											</optgroup>
											
										<?php
											}
										?>
									</select>
								</div>
							</div>
							
							<!-- <div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('office');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" id="office" readonly="readonly" />
								</div>
							</div>
							
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('SOF');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" id="sof" readonly="readonly" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('SOF_code');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" id="sof_code" readonly="readonly" />
								</div>
							</div> -->
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('date');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control datepicker" data-format='yyyy-mm-dd' id="month" name="month" readonly="readonly" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('amount');?></label>
								<div class="col-xs-8">
									<input type="number" class="form-control" name="amount" id="amount" value="0" />
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-default"><?=get_phrase('create');?></button>
								</div>
							</div>	
							
						</form>
						
					</div>
			</div>
		</div>
</div>					

<script>
	$("#frm_add_expense_update").submit(function(ev){
		
		var url = $(this).attr('action');
		var data = $(this).serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				alert(resp);
				$("#overlay").css("display","none");
				$("input[type=number]").val("0");
				$("input[type=text],select").val(null);
			},
			error:function(err,xhr,msg){
				alert(msg);
				$("#overlay").css("display","none");
			}
		});
		
		ev.preventDefault();
	});
</script>