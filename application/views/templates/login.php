<?php if ($this->session->userdata('logged_in') == 'yes'){ ?>
	<div class="form-signin">
		<p>You are currently logged in. <a href="<?php echo base_url(); ?>index.php/index.php/login/logout">Click here to log out.</a></p>
	</div>
<?php } else { ?>
<?php $attributes = array('class' => 'form-signin');
echo form_open('login/login_action', $attributes);
?>
	<h2>Please sign in</h2>
	<?php if ($this->session->userdata('logged_in') == 'incorrect'){ ?>
		<p class="warning">Invalid username or password, please try again <!--or <a href="<?php echo base_url(); ?>index.php/index.php/register">register</a>--></p>
	<?php } ?>
	<input type="text" class="input-block-level" placeholder="Email address" name="email">
	<input type="password" class="input-block-level" placeholder="Password" name="password">
	<label class="checkbox">
		<input type="checkbox" value="remember-me"> Remember me
	</label>
	<input type="hidden" name="redirect" value="<?php echo  $this->uri->uri_string() ?>" />
	<button class="btn btn-large btn-primary" type="submit">Sign in</button>
</form>
<?php } ?>