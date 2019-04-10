<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$spread = $this->db->get_where('budget_spread',array('budget_id'=>$param2))->result_object();

?>

<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo get_phrase('budget_spread');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<table class="table table-responsive">
                    		<thead>
                    			<tr>
                    				<th><?=get_phrase('month');?></th>
                    				<th><?=get_phrase('amount');?></th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    			<?php 
                    				$total = 0;
                    				foreach($spread as $row){
		                    	?>
		                    		<tr>
		                    			<td><?=$row->month;?></td>
		                    			<td><?=number_format($row->amount,2);?></td>
		                    		</tr>
		                    	<?php
		                    			$total+=$row->amount;
									}
		                    	?>
                    		</tbody>
                    		<tfoot>
                    			<tr>
                    				<th><?=get_phrase('total');?></th>
                    				<th><?=number_format($total,2);?></th>
                    			</tr>
                    		</tfoot>
                    	</table>
                    	
					</div>
				</div>
			</div>
	</div>
					