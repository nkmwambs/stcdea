<div class="row">
	<div class="col-md-12 col-sm-12 clearfix" style="text-align:center;">
		<h2 style="font-weight:200; margin:0px;"><?php echo $system_name;?></h2>
    </div>
	<!-- Raw Links -->
	<div class="col-md-12 col-sm-12 clearfix ">
		
        <ul class="list-inline links-list pull-left">
        <!-- Language Selector -->			
           <li class="dropdown language-selector">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        	<i class="entypo-user"></i> <?php echo ucfirst($this->session->userdata('name'));?> 
                    </a>

				
				<ul class="dropdown-menu <?php if ($text_align == 'right-to-left') echo 'pull-right'; else echo 'pull-left';?>">
					<li>
						<a href="<?php echo base_url();?>account/manage_profile">
                        	<i class="entypo-info"></i>
							<span><?php echo get_phrase('edit_profile');?></span>
						</a>
					</li>
					
				</ul>
				
				
			</li>
        </ul>
        
        
		<ul class="list-inline links-list pull-right">
			
			<!-- Language Selector--> 			
           <li class="dropdown language-selector">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        <i class="entypo-globe"></i> <?php echo get_phrase('language');?>
                    </a>
				
				<ul class="dropdown-menu <?php if ($text_align == 'left-to-right') echo 'pull-left'; else echo 'pull-right';?>">
					<?php
                            $fields = $this->db->list_fields('language');
                            foreach ($fields as $field)
                            {
                                if($field == 'phrase_id' || $field == 'phrase')continue;
                                ?>
                                    <li class="<?php if($this->session->userdata('current_language') == $field)echo 'active';?>">
                                        <a href="<?php echo base_url();?>multilanguage/select_language/<?php echo $field;?>">
                                            <img src="<?php echo base_url();?>assets/images/flag/<?php echo $field;?>.png" style="width:16px; height:16px;" />	
												 <span><?php echo ucfirst($field);?></span>
                                        </a>
                                    </li>
                                <?php
                            }
                            ?>
                    
				</ul>
				
			</li>
			<!-- -->
			<!--<li class="sep"></li>-->
			
			<li>
				<a href="<?php echo base_url();?>login/logout">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
	</div>
	
</div>

<hr style="margin-top:0px;" />