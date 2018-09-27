<div class="mail-header" style="padding-bottom: 27px ;">
    <!-- title -->
    <h3 class="mail-title">
        <?php echo get_phrase('write_new_message'); ?>
    </h3>
</div>

<div class="mail-compose">

    <?php echo form_open(base_url() . 'messages/message/send_new/', array('class' => 'form', 'enctype' => 'multipart/form-data')); ?>


    <div class="form-group">
        <label for="subject"><?php echo get_phrase('recipient'); ?>:</label>
        <br><br>
        <select class="form-control select2" name="reciever" required>

            <option value=""><?php echo get_phrase('select_a_user'); ?></option>
            <?php
            	$countries = $this->db->get("country");
				if($countries->num_rows() > 0){
				
				foreach($countries->result_object() as $country){	
            ?>
            <optgroup label="<?php echo $country->name; ?>">
                <?php
                $users = $this->db->get_where('user',array("auth"=>1,"country_id"=>$country->country_id))->result_array();
                foreach ($users as $row):
                    ?>

                    <option value="<?=$this->db->get_where("role",array("role_id"=>$row['role_id']))->row()->name;?>-<?php echo $row['user_id']; ?>">
                        - <?php echo $row['firstname']." ".$row['lastname']; ?></option>

                <?php endforeach; ?>
            </optgroup>
            <?php
				}
				}
            ?>
        </select>
    </div>


    <div class="compose-message-editor">
        <textarea row="2" class="form-control wysihtml5" data-stylesheet-url="assets/css/wysihtml5-color.css" 
            name="message" placeholder="<?php echo get_phrase('write_your_message'); ?>" 
            id="sample_wysiwyg"></textarea>
    </div>

    <hr>

    <button type="submit" class="btn btn-success btn-icon pull-right">
        <?php echo get_phrase('send'); ?>
        <i class="entypo-mail"></i>

    </button>
</form>

</div>