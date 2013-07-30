<?php
$attributes = array('class' => '');
$edit = false;
if ($this->uri->segment(2) == "edit"){
	$edit = true;
	$forename = $user->forename;
	$surname = $user->surname;
	$username = $username = $user->username;
	$password = $confirm = $user->password;
	$email = $user->email;
	$permission = $user->permissions;
	
	echo form_open('users/edit/'.$user->id, $attributes);
	echo "<h2>Edit $forename $surname</h2>";
} else {
	$forename = $surname = $username = $email = $password = $confirm = $job_title = $phone = $permission = $emailThem = "";
	if ($this->uri->segment(1) == "users"){
		echo form_open('users/add', $attributes);
	} else {
		echo form_open('home', $attributes);
	}
	echo '<h2>Add a User</h2>';
}	
?>
	<div class="controls controls-row">
		<input type="text" class="input-block-level" placeholder="Forename" name="forename" value="<?php echo set_value('forename', $forename); ?>">
		<?php echo form_error('forename'); ?>
		
		<input type="text" class="input-block-level" placeholder="Surname" name="surname" value="<?php echo set_value('surname', $surname); ?>">
		<?php echo form_error('surname'); ?>
		
		<?php 
		if ($edit){ 
			echo "<p>$username</p>"; ?>
			<input type="hidden" class="input-block-level" placeholder="Username" name="username" value="<?php echo set_value('username', $username); ?>">
		<?php
		} else { ?>
			<input type="text" class="input-block-level" placeholder="Username" name="username" value="<?php echo set_value('username', $username); ?>">
		<?php } ?>
		<?php echo form_error('username'); ?>
		
		<input type="text" class="input-block-level" placeholder="Email" name="email" value="<?php echo set_value('email', $email); ?>">
		<?php echo form_error('email'); ?>

		<?php if ($this->uri->segment(2) != "edit"){ ?>
			<label class="checkbox">
				Email new user? <?php echo form_checkbox('emailThem', 'accept'); //set_checkbox('emailThem', "1", $emailThem); ?>
			</label>
		<?php } ?>
		<input type="password" class="input-block-level" placeholder="Password" name="password" value="<?php echo set_value('password', $password); ?>">
		<?php echo form_error('password'); ?>
		
		<input type="password" class="input-block-level" placeholder="Confirm password" name="confirm" value="<?php echo set_value('confirm', $confirm); ?>">
		<?php echo form_error('confirm'); ?>
		
		<?php 
		if((($edit == true)&&($this->Permission->level("permissions:edit own, permissions:edit all"))) xor ($edit != true)){ ?>
			<h3>Select permission level</h3>
			<?php if ($edit == true){
				echo "<p>N.B. if you change the permission level of the account you're logged into, you will need to log out and back in again for the changesto take effect</p>";
			} ?>
			<?php 
			foreach ($permissions->result() as $perm){ ?>
				<label class="radio">
					<?php $permissionBool = FALSE;
					if ($permission == $perm->id){
						$permissionBool = TRUE;
					} ?>
					<input type="radio" name="permissions" value="<?php echo $perm->id; ?>" <?php echo set_radio('permissions', $perm->id, $permissionBool); ?>>
					<?php echo $perm->name; ?>
				</label>
				<?php 
			} ?>
			<?php echo form_error('permissions'); ?>
			<?php 
		} 
		?>
	</div>
	
	<input type="hidden" name="redirect" value="<?php if(isset($redirect)){ echo $redirect; } ?>">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
		<a href="<?php echo site_url('users') ?>" type="button" class="btn">Cancel</a>
	</div>
</form>