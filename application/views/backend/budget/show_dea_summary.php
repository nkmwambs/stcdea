<?php

/**
 * Make a model method for this
 */
$this->db->join('allocation','allocation.dea_id=dea.dea_id');
$this->db->select(array('dea_code','dea.dea_id'));
$this->db->select_sum('amount');
$this->db->group_by('allocation.dea_id');
$allocation = $this->db->get_where('dea',
array('allocation.alloc_year'=>date('Y',strtotime($param3)),'office_id'=>$param2));

/**
 * Make a model method for this
 */
//$to_date_expense = $this->budget_model->compute_dea_expense_to_date(date('Y-m-01'),$param2);
//print_r($to_date_expense);
?>
<!-- <div class="row">
	<div class="col-xs-12">
		<a onclick="PrintElem('#print_report')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			  <?=get_phrase('print');?>
			     <i class="entypo-print"></i>
		</a>
	</div>	
</div>

<hr /> 

<div class="row" id="print_report">
	<div class="col-xs-12">
			
		<table class="table table-striped datatable">   
			<thead>
				<tr>
					<th>Office</th>
					<th>DEA Code</th>
					<th>Allocated Amount</th>
					<th>Expense To Date</th>
					<th>Unabsorbed Allocation</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					$total_expense = 0;
					foreach($allocation->result_object() as $row){
				?>
					<tr>
						<td><?=$this->db->get_where('office',array('office_id'=>$param2))->row()->name;?></td>	
						<td><?=$row->dea_code;?></td>
						<td><?=number_format($row->amount,2);?></td>
						<?php
							$expense_to_date = isset($to_date_expense[$row->dea_id])?$to_date_expense[$row->dea_id]:0;
							$ubs_alloc = $row->amount - $expense_to_date;
						?>	
						<td><?=number_format($expense_to_date,2);?></td>
						<td><?=number_format($ubs_alloc,2)?></td>
					</tr>
				<?php
						$total+=$row->amount;
						$total_expense+=$expense_to_date;
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><?=get_phrase('total');?></td>
					<td><?=number_format($total,2);?></td>
					<td><?=number_format($total_expense,2);?></td>
					<td><?=number_format(($total-$total_expense),2);?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div> -->