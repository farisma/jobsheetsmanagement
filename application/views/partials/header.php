<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="<?php echo site_url() ?>"><img src="<?php echo base_url() ?>assets/img/logo.png" width="164" height="35" /></a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li class="<?php echo ($current_section == 'home') ? 'active' : '' ?>"><a href="<?php echo site_url('home') ?>">Home</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('adminpage') ?>">Admin</a></li>        
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('jobsheets') ?>">Timesheets</a></li>
		   <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('user/leaverequest') ?>">Request for leave</a></li>
       <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('alljobs') ?>">View all jobs</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('jobsheets/search') ?>">Search</a></li>
        </ul>
      </div>
      <div class="pull-right">
        <div class="btn-group">
          <?php if ( $user_logged_in ): ?>
          <a class="btn btn-small btn-inverse" href="<?php echo site_url('my-account') ?>"><i class="icon-user icon-white"></i> My account</a>
          <a class="btn btn-small btn-inverse" href="<?php echo site_url('logout') ?>">Logout</a>
          <?php else: ?>
          <a class="btn btn-small btn-inverse" href="<?php echo site_url('login') ?>">Login</a>
		  <a class="btn btn-small btn-inverse" href="<?php echo site_url('register') ?>">Register</a>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
</div>