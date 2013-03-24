<h4>Admin Panel</h4>
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <ul class="nav">
      <li class="<?php echo e($current_page == 'officers' ? 'active' : ''); ?>">
        <a href="<?php echo e(route('admin_officers')); ?>">Officers</a>
      </li>
      <?php if (Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)): ?>
        <li class="<?php echo e($current_page == 'emails' ? 'active' : ''); ?>">
          <a href="<?php echo e(route('admin_emails')); ?>">Emails</a>
        </li>
      <?php endif; ?>
      <li>
        <a href="<?php echo e(route('new_projects')); ?>">Add Project</a>
      </li>
    </ul>
  </div>
</div>