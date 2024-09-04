
<script src="https://www.google.com/recaptcha/api.js?render=6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT"></script>

<?php echo form_open('login', array('class' => 'form-signin','id' => 'form1')) ?>
	
	<h2 class="form-signin-heading">Please sign in</h2>

	<div class="control-group <?php echo (form_error('identity')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="identity" class="control-label">Email</label>
			<?php echo form_input($identity) ?>
			<?php echo form_error('identity') ?>
		</div>
	</div>

	<div class="control-group <?php echo (form_error('password')) ? 'error' : '' ?>">
		<div class="controls">
			<label for="password" class="control-label">Password</label>
			<?php echo form_input($password) ?>
			<?php echo form_error('password') ?>
		</div>
	</div>
	
	<div class="form-actions">
		<div class="pull-left">
			<label class="checkbox cb_rememberme">
				<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"') ?> Keep me logged in
			</label>
		</div>
		<div class="pull-right">
		<input type="hidden" id="token" name="token">
			<button id="btn_submit" class="btn tn-larbge btn-primary" type="submit">Log In</button>
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
