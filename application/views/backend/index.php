<?php
	$system_name        =	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
	$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
	$text_align         =	$this->db->get_where('settings' , array('type'=>'text_align'))->row()->description;

	$skin_colour        =   $this->db->get_where('settings' , array('type'=>'skin_colour'))->row()->description;
	$active_sms_service =   $this->db->get_where('settings' , array('type'=>'active_sms_service'))->row()->description;
	?>
<!DOCTYPE html>
<html lang="en" dir="<?php if ($text_align == 'right-to-left') echo 'rtl';?>">
<head>
	
	<title><?php echo $page_title;?> | <?php echo $system_title;?></title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />
	<meta name="author" content="Techsys Softwares" />
	
	

	<?php include 'includes_top.php';?>
	<?php include 'privileges.php';?>
</head>
<body class="page-body <?php if ($skin_colour != '') echo 'skin-' . $skin_colour;?> page-fade-only">
	<div class="page-container sidebar-collapsed <?php if ($text_align == 'right-to-left') echo 'right-sidebar';?>" ><!--sidebar-collapsed-->
		<?php include 'navigation.php';?>	
		<div class="main-content">
		
			<?php include 'header.php';?>
					<div class="row">
						<div class="col-xs-12">
							<h3 style="" class="pull-left">
						       <i class="entypo-right-circled"></i> 
									<?php echo $page_title;?>
						    </h3>
						    
						    <span title="<?=get_phrase('back');?>" style="cursor: pointer;" class="fa fa-reply pull-right" onclick="javascript:go_back();"></span>
						</div>
					</div>
					
				  <hr />  
				<div class="page-content">	
		           <!--Showing Progress GIF. Must be available in evert form-->
					
					<?php include $view_type.'/'.$page_name.'.php';?>
					<?php include 'debug.php';?>
				</div>
			<?php include 'footer.php';?>

		</div>
		<?php //include 'chat.php';?>
        	
	</div>
    <?php include 'modal.php';?>
    <?php include 'includes_bottom.php';?>
    <script src="<?=base_url();?>assets/js/ci-custom-ajax.js"></script>
</body>
</html>