<div class="row">
	<div class="col-xs-12">
		<?php
			//print_r($results);
		?>

				<ul class="nav nav-tabs bordered"><!-- available classes "bordered", "right-aligned" -->
					<?php
						$cnt = 0;
						foreach($results as $key=>$result){
					?>
						<li class="<?php if($cnt == 0) echo 'active';?>">
							<a href="#<?=$key;?>" data-toggle="tab">
								<span class="visible-xs"><i class="entypo-home"></i></span>
								<span class="hidden-xs"><?=ucfirst($key);?></span>
							</a>
						</li>
					<?php
						$cnt++;
						}
					?>
					
				</ul>
				
				<div class="tab-content">
					
						<?php
							$cnt = 0;
							foreach($results as $key=>$result){
						?>
							<div class="tab-pane <?php if($cnt == 0) echo 'active';?>" id="<?=$key;?>">
									<p></p>
									
									<div class="row">
										<a href="<?=base_url();?>account/add_user_restriction" class="col-xs-12 <?=get_access('add_user_restriction','view_user_restriction');?>">
											<div class="btn btn-default"><i class="fa fa-plus"></i> Add Restriction</div>
										</a>
									</div>
									
									<p></p>															
									<table class="table table-striped">
										<thead>
											<tr>
												<th>User Name</th>
												<th>Restricted Objects</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												foreach($result as $row_key=>$row_value){
											?>				
												<tr>
													<td>
														<?=$row_key;?>
													</td>
													<td>
														<ul>
														<?php 
															$object_array = array_column($row_value, 'restriction_value_id');
															$names = array();
															foreach($object_array as $obj_id){
																	
																echo "<li>".$this->crud_model->get_type_name_by_id($key,$obj_id)."</li>";
															}
															
															//echo implode(", ", $names)
														?>
														</ul>
													</td>
													<td>
														<div class="<?=get_access('edit_user_restriction','view_user_restriction')?>"><i style="cursor: pointer;" class="fa fa-pencil"></i></div>
														<div class="<?=get_access('delete_user_restriction','view_user_restriction')?>"><i style="cursor: pointer;" class="fa fa-trash"></i></div>
													</td>
												</tr>
											<?php
												}
											?>			
										</tbody>
									</table>
									
							</div>
						
						<?php
								$cnt++;
							}
						?>
					
				</div>
			
		
	</div>
</div>