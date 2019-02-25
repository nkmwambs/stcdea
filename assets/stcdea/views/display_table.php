<div class="row">
	<div class="col-xs-12">
		<button class="btn btn-default">Add A Record</button>
	</div>
</div>
<hr/>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<?php foreach($table_fields as $field):	?>
						<th><?=$field;?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($table_results as $row):?>
					<tr>
						<?php
							foreach($row as $field_value):?>
							<td><?=$field_value;?></td>
						<?php endforeach;?>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>