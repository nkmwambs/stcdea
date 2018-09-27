<div class="row">
	<div class="row">
		<div class="col-sm-12 inner-progress"></div>
	</div>
	<div class="col-md-12">

    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">

			<li class="">
            	<a href="#list" data-toggle="tab"><i class="entypo-user"></i>
					<?php echo get_phrase('manage_profile');?>
                    	</a>
            </li>

			<li class="">
            	<a href="#password" data-toggle="tab"><i class="entypo-lock"></i>
					<?php echo get_phrase('change_password');?>
                    	</a>
            </li>


            <li class="active">
            	<a href="#users" data-toggle="tab"><i class="fa fa-user-plus"></i>
					<?php echo get_phrase('manage_users');?>
                    	</a>
            </li>



		</ul>
    	<!------CONTROL TABS END------>


		<div class="tab-content">

			<div class="tab-pane box" id="list" style="padding: 5px">
               <div class="box-content">
					<?php
                     	echo form_open(base_url() . 'account/manage_users/update_profile_info/'.$user->user_id  , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));
                    ?>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('first_name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" readonly="readonly" class="form-control"  value="<?php echo $user->firstname;?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('last_name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" readonly="readonly" class="form-control"  value="<?php echo $user->lastname;?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email" value="<?php echo $user->email;?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('update_profile');?></button>
                              </div>
								</div>
                        </form>
             </div>
			</div>

			<!--End Profile -->

			<div class="tab-pane box" id="password" style="padding: 5px">
               <div class="box-content padded">
					<?php
                     	echo form_open(base_url() . 'account/manage_users/change_password/'.$user->user_id , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));
                     ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('current_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('new_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="new_password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('confirm_new_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="confirm_new_password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('update_profile');?></button>
                              </div>
								</div>
                        </form>
             </div>
			</div>

            <!-- END PASSWORD --->

			          <!--Manage Users-->
            <div class="tab-pane box active" id="users" style="padding: 5px">
            	<div class="add_user">
            		<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_user_add/');"
					class="btn btn-primary pull-right">
						<i class="entypo-plus-circled"></i>
							<?php echo get_phrase('add_user');?>
					</a>
				</div>
				<br><hr>

				<table class="table table-striped datatable" id="table_export">
					<thead>
						<tr>
							<th><?php echo get_phrase('action');?></th>
							<th><?php echo get_phrase('first_name');?></th>
							<th><?php echo get_phrase('last_name');?></th>
							<th><?php echo get_phrase('email');?></th>
							<th><?php echo get_phrase('country');?></th>
							<th><?php echo get_phrase('role');?></th>
							<th><?php echo get_phrase('profile');?></th>
							<th><?php echo get_phrase('status');?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($users as $user):
						?>
							<tr>
								<td>
									 <div class="btn-group">
			                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
			                                        Action <span class="caret"></span>
			                                    </button>
			                                    <ul class="dropdown-menu dropdown-default pull-left" role="menu">

			                                        <li>
			                                            <a href="#users" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_account/<?php echo $user->user_id;?>');">
			                                                <i class="entypo-eye"></i>
			                                                    <?php echo get_phrase('view');?>
			                                                </a>
			                                        </li>
			                                        <li class="divider"></li>

			                                        <!-- USER EDITING  -->
			                                        <li class="edit_user">
			                                            <a href="#users" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_user_edit/<?php echo $user->user_id;?>');">
			                                                <i class="entypo-pencil"></i>
			                                                    <?php echo get_phrase('edit');?>
			                                                </a>
			                                        </li>
			                                        <li class="divider update_user"></li>

			                                         <li class="change_scope">
			                                            <a href="#users" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_scope_assignment/<?php echo $user->user_id;?>');">
			                                                <i class="entypo-level-down"></i>
			                                                    <?php echo get_phrase('change_scope');?>
			                                                </a>
			                                        </li>
			                                        <li class="divider change_scope"></li>


			                                        <!-- SUSPEND USER  -->
			                                        <li class="suspend_user">
			                                            <a  href="#users" onclick="confirm_action('<?php echo base_url();?>account/manage_users/user_suspend/<?php echo $user->user_id;?>');">
			                                                <i class="<?php if($user->auth === '1' ){echo "entypo-cancel";}else{echo "entypo-check";};?>"></i>
			                                                    <?php if($user->auth === '1') {echo get_phrase('suspend');}else{echo  get_phrase('activate');};?>
			                                                </a>
			                                        </li>

			                                        <li class="divider suspend_user"></li>

			                                        <!-- USER DELETION  -->
			                                        <li class="delete_user" >
			                                            <a href="#users" onclick="confirm_dialog('<?php echo base_url();?>account/manage_users/user_delete/<?php echo $user->user_id;?>');">
			                                                <i class="entypo-trash"></i>
			                                                    <?php echo get_phrase('delete');?>
			                                                </a>
			                                        </li>
			                                    </ul>
			                                </div>
								</td>
								<td><?=$user->firstname;?></td>
								<td><?=$user->lastname;?></td>
								<td><?=$user->email;?></td>
								<td><?=$this->crud_model->get_type_name_by_id("country",$user->country_id);?></td>
								<td><?=$this->crud_model->get_type_name_by_id("role",$user->role_id);?></td>
								<td><?=$this->crud_model->get_type_name_by_id("profile",$user->profile_id);?></td>
								<td><?=$user->auth==='1'?get_phrase("active"):get_phrase("suspended");?></td>
							</tr>

						<?php
							endforeach;
						?>
            		</tbody>
				</table>
         </div>

		</div>
	</div>

</div>

<script>
	$(document).ready(function(){

		    if (location.hash) {
			        $("a[href='" + location.hash + "']").tab("show");
			    }
			    $(document.body).on("click", "a[data-toggle]", function(event) {
			        location.hash = this.getAttribute("href");
			    });

			$(window).on("popstate", function() {
			    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
			    $("a[href='" + anchor + "']").tab("show");

		});



		var datatable = $('#table_export').DataTable({
		       dom: '<Bf><"col-sm-12"rt><ip>',
		       //sDom:'r',
		       pagingType: "full_numbers",
		       buttons: [
		           'csv', 'excel', 'print'
		       ],
		       stateSave: true

		   });



	});
</script>
