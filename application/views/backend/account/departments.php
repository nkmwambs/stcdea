<?php 

foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
 
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
 
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div class="row">
	<div class="col-xs-12">
		<a href="<?=base_url();?>account/upload_setup/department" class="btn btn-default"><?=get_phrase("upload_function");?></a>
	</div>	
</div>

<hr />

<div class="row">
	<div class="col-sm-12">
		<?php echo $output; ?>
	</div>
</div>