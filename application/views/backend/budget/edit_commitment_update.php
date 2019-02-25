<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo $page_title;?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<a href="#" onclick="javascript:go_back();" class="btn btn-default">Show Listing</a>
                    	
                    	<hr/>
						
						<?php 
							
							$arr = (array)$commitment;
							//print_r($arr);
							echo form_open(base_url() . 'budget/update_commitment_update/'.array_shift($arr), array('id'=>'frm_update_commitment_update', 'class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
							//print_r($arr);
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
													<option value="<?=$dea->dea_id;?>" <?php if($dea->dea_id == $commitment->dea_id) echo "selected";?> ><?=$dea->dea_code;?> [<?=$dea->office;?>]</option>
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
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('LPO_number');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" value="<?=$commitment->lpo;?>" id="lpo" name="lpo" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('description');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" value="<?=$commitment->description;?>"  id="description" name="description"/>
								</div>
							</div>
														
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('commitment_date');?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control datepicker" value="<?=$commitment->month;?>" data-format='yyyy-mm-dd' id="month" name="month" readonly="readonly" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-4"><?=get_phrase('amount');?></label>
								<div class="col-xs-8">
									<input type="number" class="form-control" name="amount" id="amount" value="<?=$commitment->amount;?>" />
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-default"><?=get_phrase('edit');?></button>
								</div>
							</div>	
							
						</form>
				</div>
			</div>
		</div>
	</div>
						