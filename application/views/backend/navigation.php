<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo" style="">
            <a href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url();?>uploads/logo.png"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>

    <div style=""></div>
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php echo get_access('dashboard','system'); if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url();?>dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>
        
        <!-- BVA Updates -->
        
        <li class="<?=get_access('view_monthly_updates','system');?> 
        	<?php
		        if ($page_name == 'expense_updates' ||
		                $page_name == 'active_commitments' ||
		                    $page_name == 'bva_updates')
		                        echo 'opened active';
		        ?>  
		 ">
        	
            <a href="#">
                <i class="entypo-cw"></i>
                <span><?php echo get_phrase('monthly_updates'); ?></span>
            </a>
            
            <ul>
            	<li class="<?=get_access('view_expense_update','view_monthly_updates')?> <?php if ($page_name == 'expense_updates') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/expense_updates"); ?>" class="ajax-content">
	                      <span><i class="entypo-docs"></i> <?php echo get_phrase('expense_update'); ?></span>
	                  </a>
	            </li>
	            
	            <li class="<?=get_access('view_commitment_update','view_monthly_updates')?> <?php if ($page_name == 'commitments_updates') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/commitments_updates"); ?>" class="ajax-content">
	                      <span><i class="entypo-rss"></i> <?php echo get_phrase('active_commitments'); ?></span>
	                  </a>
	            </li>
	            
	            <li class="<?=get_access('view_BVA_update','view_monthly_updates')?> <?php if ($page_name == 'bva_updates') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/bva_updates"); ?>" class="ajax-content">
	                      <span><i class="entypo-flash"></i> <?php echo get_phrase('BVA_update'); ?></span>
	                  </a>
	            </li>
            </ul>
        </li>
        
        <!--Budget Menu-->
        
        <li class="<?=get_access('view_budget','system');?> <?php
        if ($page_name == 'staff_costing' ||
                $page_name == 'thematic_costing' ||
                    $page_name == 'non_thematic_costing')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-list"></i>
                <span><?php echo get_phrase('budget'); ?></span>
            </a>
            <ul>
					<?php
						$budget_types_obj = $this->db->get_where('budget_section',array('status'=>1));
						
						if($budget_types_obj->num_rows()>0){
							$view_name = "";
							foreach($budget_types_obj->result_object() as $row){
								$view_name = strtolower(str_replace(" ", "_", $row->name));
					?>
							<li class="<?=get_access('view_'.$view_name.'_budget','view_budget')?> <?php if ($page_name == 'view_budget') echo 'active'; ?> ">
			                    <a href="<?php echo base_url("Budget/view_budget/".$view_name); ?>" class="ajax-content">
			                        <span><i class="entypo-users"></i> <?php echo get_phrase($row->name); ?></span>
			                    </a>
			                </li>
					<?php 
							}
						}
					?>
	                
            </ul>
        </li>
        
        <!--Reports-->
        
        <li class="<?=get_access('view_reports','system');?> 
        	<?php
		        if (
		        	// $page_name == 'dea_absorption_report' ||
		                $page_name == 'budget_gap_report')
		                        echo 'opened active';
		        ?>  
		 ">
        	
            <a href="#">
                <i class="entypo-chart-bar"></i>
                <span><?php echo get_phrase('reports'); ?></span>
            </a>
            
            <ul>
            	<!-- <li class="<?=get_access('view_dea_absorption_report','view_reports')?> <?php if ($page_name == 'dea_absorption_report') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/dea_absorption_report"); ?>" class="ajax-content">
	                      <span><i class="entypo-battery"></i> <?php echo get_phrase('burn_rate_report'); ?></span>
	                  </a>
	            </li> -->
	            
	            <li class="<?=get_access('view_overall_budget','view_reports')?> <?php if ($page_name == 'overall_budget') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/overall_budget"); ?>" class="ajax-content">
	                      <span><i class="entypo-traffic-cone"></i> <?php echo get_phrase('overall_budget'); ?></span>
	                  </a>
	            </li>
	            
	            <li class="<?=get_access('view_budget_gap_report','view_reports')?> <?php if ($page_name == 'budget_gap_report') echo 'active'; ?> ">
	                  <a href="<?php echo base_url("Budget/budget_gap_report"); ?>" class="ajax-content">
	                      <span><i class="entypo-ticket"></i> <?php echo get_phrase('funding_gap_report'); ?></span>
	                  </a>
	            </li>
	            
            </ul>
        </li>
        
        <!-- MESSAGES -->
        <li class="<?php if ($page_name == 'messages') echo 'active'; ?> ">
            <a href="<?php echo base_url();?>messages/message">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('messages'); ?></span>
            </a>
        </li>


        <!-- SETTINGS -->

        <li class="<?=get_access('manage_system_settings','system');?> <?php
        if ($page_name == 'system_settings' ||
                $page_name == 'manage_language' ||
                    $page_name == 'sms_settings')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-lifebuoy"></i>
                <span><?php echo get_phrase('settings'); ?></span>
            </a>
            <ul>

	                <li class="<?=get_access('manage_general_settings','manage_system_settings')?> <?php if ($page_name == 'system_settings') echo 'active'; ?> ">
	                    <a href="<?php echo base_url("settings/system_settings"); ?>" class="ajax-content">
	                        <span><i class="entypo-cog"></i> <?php echo get_phrase('general_settings'); ?></span>
	                    </a>
	                </li>


	                <li class="<?=get_access('manage_sms_settings','manage_system_settings')?> <?php if ($page_name == 'sms_settings') echo 'active'; ?> ">
	                    <a href="<?php echo base_url("settings/sms_settings"); ?>" class="ajax-content">
	                        <span><i class="entypo-mobile"></i> <?php echo get_phrase('sms_settings'); ?></span>
	                    </a>
	                </li>

	                <li class="<?=get_access('manage_language','manage_system_settings')?> <?php if ($page_name == 'manage_language') echo 'active'; ?> ">
	                    <a href="<?php echo base_url("settings/manage_language"); ?>" class="ajax-content">
	                        <span><i class="entypo-language"></i> <?php echo get_phrase('language_settings'); ?></span>
	                    </a>
	                </li>

            </ul>
        </li>



		<!-- SETTINGS -->
        <li class="<?=get_access('manage_accounts','system')?> <?php
        if ($page_name == 'manage_users' ||
			$page_name == 'offices' ||
            $page_name == 'departments'||
            $page_name == 'positions' ||
            $page_name == 'staff' ||
            $page_name == 'account_groups' ||
            $page_name == 'sof' ||
            $page_name == 'dea' ||
			$page_name == 'profiles' ||
			$page_name == 'mail_templates')

            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
            <ul>
            	 <!--ACCOUNT SET UP PARAMETERS-->
                <li class="<?=get_access('manage_setup_parameters','manage_accounts')?> <?php
                		if ($page_name == 'offices' ||
                			$page_name == 'departments'||
                			$page_name == 'positions' ||
                			$page_name == 'staff' ||
							$page_name == 'profiles' ||
							$page_name == 'account_groups' ||
							$page_name == 'sof' ||
							$page_name == 'dea' ||
							$page_name == 'mail_templates')

                			echo 'opened active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-loop"></i> <?php echo get_phrase('setup'); ?></span>
                    </a>
                    <ul>
                        <li class="<?=get_access('setup_offices','manage_setup_parameters')?> <?php if ($page_name == 'offices') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/offices" class="">
                                <span><i class="entypo-globe"></i><?php echo get_phrase('field_offices'); ?></span>
                            </a>
                        </li>

                        <li class="<?=get_access('setup_functions','manage_setup_parameters')?> <?php if ($page_name == 'departments') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/departments"  class="">
                                    <span><i class="entypo-progress-3"></i><?php echo get_phrase('themes'); ?></span>
                            </a>
                        </li>

                         <li class="<?=get_access('setup_positions','manage_setup_parameters')?> <?php if ($page_name == 'positions') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/positions"  class="">
                                    <span><i class="entypo-bag"></i><?php echo get_phrase('positions'); ?></span>
                            </a>
                        </li>
                        
                        <li class="<?=get_access('setup_staff','manage_setup_parameters')?> <?php if ($page_name == 'staff') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/staff"  class="">
                                    <span><i class="entypo-user"></i><?php echo get_phrase('staff'); ?></span>
                            </a>
                        </li>
                        
                        <li class="<?=get_access('setup_restriction','manage_setup_parameters')?> <?php if ($page_name == 'restriction') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/restriction"  class="">
                                    <span><i class="entypo-lock"></i><?php echo get_phrase('field_restrictions'); ?></span>
                            </a>
                        </li>
                        
                        <!-- <li class="<?=get_access('setup_account_themes','manage_setup_parameters')?> <?php if ($page_name == 'account_themes') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/account_themes"  class="">
                                    <span><i class="entypo-feather"></i><?php echo get_phrase('account_themes'); ?></span>
                            </a>
                        </li>
                        
                        <li class="<?=get_access('setup_account_groups','manage_setup_parameters')?> <?php if ($page_name == 'account_groups') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/account_groups"  class="">
                                    <span><i class="entypo-bucket"></i><?php echo get_phrase('account_groups'); ?></span>
                            </a>
                        </li> -->
                        
                        <li class="<?=get_access('setup_budget_account','manage_setup_parameters')?> <?php if ($page_name == 'budget_account') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/budget_account"  class="">
                                    <span><i class="entypo-vcard"></i><?php echo get_phrase('budget_account'); ?></span>
                            </a>
                        </li>
                        
                        <li class="<?=get_access('setup_sof','manage_setup_parameters')?> <?php if ($page_name == 'sof') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>Budget/sof"  class="">
                                    <span><i class="entypo-cc-share"></i><?php echo get_phrase('SOF_setup'); ?></span>
                            </a>
                        </li>
                        
                        <li class="<?=get_access('setup_dea','manage_setup_parameters')?> <?php if ($page_name == 'dea') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>Budget/dea"  class="">
                                    <span><i class="entypo-cc-nd"></i><?php echo get_phrase('DEA_setup'); ?></span>
                            </a>
                        </li>
                        

                        <li class="<?=get_access('setup_user_profiles','manage_setup_parameters')?> <?php if ($page_name == 'profiles') echo 'active'; ?>">
                        	<a href="<?php echo base_url(); ?>account/profiles"  class="">
                                    <span><i class="entypo-tag"></i><?php echo get_phrase('user_profiles'); ?></span>
                            </a>
                        </li>
                        
                        

                    </ul>
                </li>

                <li class="<?=get_access('manage_users','manage_accounts')?> <?php if ($page_name == 'manage_users') echo 'active'; ?> ">
                    <a href="<?php echo base_url("account/manage_users"); ?>">
                        <span><i class="entypo-users"></i> <?php echo get_phrase('manage_users'); ?></span>
                    </a>
                </li>

            </ul>
        </li>

           
    </ul>

</div>
