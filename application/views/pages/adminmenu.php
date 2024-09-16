		<ul class="nav">
		  <li class="<?php echo ($current_section == 'page') ? 'active' : '' ?>"><a href="<?php echo site_url('list-clients') ?>">Client List Management</a></li>
		  <li class="<?php echo ($current_section == 'page') ? 'active' : '' ?>"><a href="<?php echo site_url('add-division') ?>">Add Division</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('list-jobs') ?>">Job No. Management</a></li>
          <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('list-users') ?>">User Management</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('vacation/leavemanagement') ?>">Leave Management</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('add-jobsheet') ?>">Add Time</a></li>
          <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('list_project_types') ?>">Add Project Type</a></li>
		  <li class="<?php echo ($current_section == 'another-page') ? 'active' : '' ?>"><a href="<?php echo site_url('view_reports') ?>">Reports</a></li>
        </ul>