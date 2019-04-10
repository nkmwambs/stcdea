<?php

$accounts_with_dea = $this->budget_model->accounts_with_dea($office_id,$start_date,$related_table,$budget_type);

//print_r($accounts_with_dea);
			
?>

<div class="row">
	<div class="col-xs-12">
		
		<a onclick="javascript:go_back();" class="btn btn-default hidden-print pull-left">
			  <?=get_phrase('back');?>
		</a>
		
		<a onclick="PrintElem('#print_report')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			  <?=get_phrase('print');?>
			     <i class="entypo-print"></i>
		</a>
	</div>	
</div>

<hr /> 

<div id="print_report" class="row">
	<div class="col-xs-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?=get_phrase('Staff/Account')?></th>
					<!-- <th><?=get_phrase('Staff/Account_Code')?></th> -->
					<th><?=get_phrase('total_budget')?></th>
					<th><?=get_phrase('DEA_allocation')?></th>
					<th><?=get_phrase('funding_gap');?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$grand_total = 0;
					$total_gap = 0;
					$grand_budget = 0;
					foreach($accounts_with_dea as $row){
						$code = $related_table."_code";
				?>
					<tr>
						<td><?=$row->name;?></td>
						<!-- <td><?=$row->$code;?></td> -->
						<td><?=number_format($row->amount,2);?></td>
						<td>
							<table class="table table-striped">
								<thead>
									<tr>
										<td><?=get_phrase('DEA');?></td>
										<td><?=get_phrase('allocation');?></td>
									</tr>
								</thead>
								<tbody>
							<?php
									$amount = 0;
									foreach($row->allocation as $value){
							?>
									<tr>
										<td>
											<?php 
											
												$dea = $this->db->get_where("dea",array("dea_id"=>$value->dea_id))->row();
												echo $dea->dea_code.": ".$dea->description;
											?>
										</td>
										<td><?=number_format($value->alloc_amount,2);?></td>
									</tr>
																							
							<?php
										
										$amount +=$value->alloc_amount;
										$grand_total +=$value->alloc_amount;
									}
							?>
								</tbody>
								<tfoot>
									<tr>
										<td><?=get_phrase('total')?></td>
										<td><?=number_format($amount,2);?></td>
									</tr>
								</tfoot>
							</table>
						</td>
						<td><?=number_format($amount - $row->amount,2);?></td>	
					</tr>
				<?php
						$total_gap +=$amount - $row->amount;
						$grand_budget += $row->amount;
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><?=get_phrase('grand_total');?></td>
					<td><?=number_format($grand_budget,2)?></td>
					<td><?=number_format($grand_total,2);?></td>
					<td><?=number_format($total_gap,2);?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>