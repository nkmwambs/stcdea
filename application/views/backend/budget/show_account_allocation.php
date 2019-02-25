<?php
$account_deas = $this->db->get_where('allocation',array('budget_id'=>$param2,'alloc_year'=>$param3));

if($account_deas->num_rows() > 0){
	$result = $account_deas->result_object();
	//print_r($result);
?>

<div class="row">
	<div class="col-xs-12">
		<?php
			$total_budget = 0;
			$this->db->join('budget_spread','budget_spread.budget_id=budget.budget_id');
			$this->db->select('sum(amount) as amount');
			$this->db->group_by('budget_spread.budget_id');
			$budget = $this->db->get_where('budget',array('budget.budget_id'=>$param2));
			$total_budget = $budget->num_rows() > 0?$budget->row()->amount:0;
		?>
		<span style="font-weight: bold;">Total Budget:</span> <span  style="font-weight: bold;"><?=number_format($total_budget,2);?></span>
	</div>
</div>

<hr />

<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>DEA Code</th>
					<th>Year</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					foreach($result as $row){
				?>
					<tr>
						<td><?=$this->db->get_where('dea',array('dea_id'=>$row->dea_id))->row()->dea_code;?></td>
						<td><?=$row->alloc_year;?></td>
						<td><?=number_format($row->amount,2);?></td>
					</tr>
				<?php
						$total += $row->amount;
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><?=get_phrase('total')?></td>
					<td><?=number_format($total,2)?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<?php
}else{
?>
<div class="row">
	<div class="col-xs-12">
		<div class="well">No allocation found for the year <?=$param3;?></div>
	</div>
</div>
<?php	
}
?>
