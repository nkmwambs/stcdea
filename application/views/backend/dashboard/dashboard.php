	<?php
		//echo in_array('manage_users', $this->session->privileges);
	?>

		<div class="row <?=get_access('quicklinks','dashboard')?>">
			<div class="col-xs-12">

					<div class="dropdown pull-left">
						<div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-list"></i> Check Budget <span class="caret"></span>	
						</div>
						<ul class="dropdown-menu">
						    <li><a href="<?php echo base_url("Budget/view_budget/staff_cost"); ?>"><i class="fa fa-users"></i> <?=get_phrase('staff_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="<?php echo base_url("Budget/view_budget/thematic_cost"); ?>"><i class="fa fa-hand-lizard-o"></i> </ia><?=get_phrase('thematic_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="<?php echo base_url("Budget/view_budget/non_thematic_cost"); ?>"><i class="fa fa-fire"></i> <?=get_phrase('non_thematic_cost');?></a></li>
						 </ul>
					</div>
					
					<div class="dropdown pull-left">
						<div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-paw"></i> Allocate DEA <span class="caret"></span>	
						</div>
						<ul class="dropdown-menu">
						    <li><a href="<?php echo base_url("Budget/allocate_dea_spread/".$this->session->office_id."/staff_cost/".strtotime('first day of january',strtotime(date('Y-m-d')))."/".strtotime('last day of december',strtotime(date('Y-m-d'))));?>"><i class="fa fa-users"></i> <?=get_phrase('staff_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="<?php echo base_url("Budget/allocate_dea_spread/".$this->session->office_id."/thematic_cost/".strtotime('first day of january',strtotime(date('Y-m-d')))."/".strtotime('last day of december',strtotime(date('Y-m-d'))));?>"><i class="fa fa-hand-lizard-o"></i> </ia><?=get_phrase('thematic_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="<?php echo base_url("Budget/allocate_dea_spread/".$this->session->office_id."/non_thematic_cost/".strtotime('first day of january',strtotime(date('Y-m-d')))."/".strtotime('last day of december',strtotime(date('Y-m-d'))));?>"><i class="fa fa-fire"></i> <?=get_phrase('non_thematic_cost');?></a></li>
						 </ul>
					</div>
					
					<!-- <div class="dropdown pull-left">
						<div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-line-chart"></i> DEA Allocation Report <span class="caret"></span>	
						</div>
						<ul class="dropdown-menu">
						    <li><a href="<?php echo base_url("Budget/view_budget/staff_cost"); ?>"><i class="fa fa-users"></i> <?=get_phrase('staff_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="#"><i class="fa fa-hand-lizard-o"></i> </ia><?=get_phrase('thematic_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="#"><i class="fa fa-fire"></i> <?=get_phrase('non_thematic_cost');?></a></li>
						 </ul>
					</div>
					
					<div class="dropdown pull-left">
						<div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-pie-chart"></i> Budget Gap Report <span class="caret"></span>	
						</div>
						<ul class="dropdown-menu">
						    <li><a href="<?php echo base_url("Budget/view_budget/staff_cost"); ?>"><i class="fa fa-users"></i> <?=get_phrase('staff_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="#"><i class="fa fa-hand-lizard-o"></i> </ia><?=get_phrase('thematic_cost');?></a></li>
						     <li class="divider"></li>
						    <li><a href="#"><i class="fa fa-fire"></i> <?=get_phrase('non_thematic_cost');?></a></li>
						 </ul>
					</div> -->
					
			</div>
		</div>
		
		<hr />


		<div class="row">
			<div class="col-sm-3 <?=get_access('view_users_online_tile','dashboard')?>">
			
				<div class="tile-stats tile-blue">
					<div class="icon"><i class="entypo-suitcase"></i></div>
					<?php
						$count_of_users_online = $this->db->get_where('user',array('online'=>1))->num_rows();
					?>
					<div class="num"><?=$count_of_users_online;?></div>
					
					<h3>Online</h3>
					
					<p><?=get_phrase("users_online");?>.</p>
				</div>
				
			</div>
		</div>
		
		
		