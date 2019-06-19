<?php
$this->db->select(array('commitment_detail.commitment_detail_id','commitment.commitment_id','commitment.month','commitment.lpo',
'commitment_detail.description','commitment_detail.dea_id','commitment_detail.amount','sof.name as sof',
'office.name as office','commitment_detail.status'));

$this->db->join('commitment','commitment.commitment_id=commitment_detail.commitment_id');
$this->db->join('dea','dea.dea_id=commitment_detail.dea_id');
$this->db->join('office','office.office_id=dea.office_id');
$this->db->join('sof','sof.sof_id=dea.sof_id');
$commitment_detail = $this->db->get_where('commitment_detail',array('commitment_detail.commitment_id'=>$param2))->result_object();

//print_r($commitment_detail);
?>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">
					Commitment Details
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?=get_phrase('office');?></th>
							<th><?=get_phrase('S.O.F');?></th>
							<th><?=get_phrase('DEA_id');?></th>
							<th><?=get_phrase('description');?></th>
							<th><?=get_phrase('amount');?></th>
							<th><?=get_phrase('action');?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($commitment_detail  as $detail){
						?>
							<tr>
								<td><?=$detail->office;?></td>
								<td><?=$detail->sof;?></td>
								<td><?=$detail->dea_id;?></td>
								<td><?=$detail->description;?></td>
								<td><?=$detail->amount;?></td>
								<?php
									$btn_color = 'btn-info';
									$btn_html = "Pay";
									if($detail->status == 1){
										$btn_color = 'btn-success';
										$btn_html = "Paid";
									}
								?>
								<td><div onclick="pay_single_lpo_item(this);" id="<?=$detail->commitment_detail_id;?>" class="btn <?=$btn_color;?>"><?=get_phrase($btn_html);?></div></td>
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
	function pay_single_lpo_item(elem){
		var commitment_detail_id = $(elem).attr('id');
		var url = '<?=base_url();?>/budget/pay_lpo_item/'+commitment_detail_id;		
		$.ajax({
			url:url,
			beforeSend:function(){
				
			},
			success:function(resp){
				alert(resp);
			},
			error:function(obj,error){
				alert(error);
			}
		});
		
		$(elem).toggleClass('btn-info btn-success')
		$(elem).html('Paid');
	}
</script>