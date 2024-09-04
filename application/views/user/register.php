<script src="https://www.google.com/recaptcha/api.js?render=6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT"></script>

<?php echo form_open('register', array('class' => 'form-signin','id' => 'form1')) ?>
	
	<h2 class="form-signin-heading">Register your Account</h2>

	<div class="control-group <?php echo (form_error('username')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="username" class="control-label">Username</label>
			<?php echo form_input($username) ?>
			<?php echo form_error('username') ?>
		</div>
	</div>

	<div class="control-group <?php echo (form_error('password')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="password" class="control-label">Password</label>
			<?php echo form_input($password) ?>
			<?php echo form_error('password') ?>
		</div>
	</div>
		<div class="control-group <?php echo (form_error('firstname')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="firstname" class="control-label">First Name</label>
			<?php echo form_input($firstname) ?>
			<?php echo form_error('firstname') ?>
		</div>
	</div>
	
	<div class="control-group <?php echo (form_error('lastname')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="lastname" class="control-label">Last Name</label>
			<?php echo form_input($lastname) ?>
			<?php echo form_error('lastname') ?>
		</div>
	</div>
	<div class="control-group <?php echo (form_error('email')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="email" class="control-label">Email</label>
			<?php echo form_input($email) ?>
			<?php echo form_error('email') ?>
		</div>
	</div>
	<div class="control-group <?php echo (form_error('group')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="group" class="control-label">User type</label>
			<?php $options = array(
                  '1'  => 'Admin',
                  '2'    => 'Member'             
                );?>
			<?php echo form_dropdown('group', $options, '1');
 ?>
			<?php echo form_error('group') ?>
		</div>
	</div>
	
	<div class="form-actions">
		
		<div class="pull-right">
		<input type="hidden" id="token" name="token">
			<button id="btn_submit" class="btn tn-larbge btn-primary" type="submit">Register</button>
		</div>
	</div>

<?php echo form_close() ?>

<div class="section_forgot">
	&raquo; <a href="<?php echo site_url('forgot-password') ?>">Forgot your password?</a>
</div>

<script>
window.addEventListener('load', function () {
document.getElementById('btn_submit').addEventListener(
  'click',
  function(e) {
  e.preventDefault();
  grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log(token);
              if(token != "")
              {
                document.getElementById("token").value = token;
                document.getElementById("form1").submit(); 
              }
              

          });
        });
  });

});

</script>
