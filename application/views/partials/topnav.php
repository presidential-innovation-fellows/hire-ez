<?php if (Auth::check()): ?>
  <div class="navbar navbar-static-top">
    <div class="navbar-inner">
      <div class="container">
        <div class="nav-collapse collapse">
          <ul class="nav">
            <?php if (Auth::user()->officer): ?>
              <li>
                <a href="<?php echo e( route('vendors') ); ?>">Applicants</a>
              </li>
              <li>
                <a href="<?php echo e( route('my_projects') ); ?>">Projects</a>
              </li>
              <?php if (Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)): ?>
                <li>
                  <a href="<?php echo e(route('admin_home')); ?>">Admin</a>
                </li>
              <?php endif; ?>
            <?php endif; ?>
          </ul>
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo e(Auth::user()->email); ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="<?php echo e(route('account')); ?>">Account Settings</a>
                </li>
                <li>
                  <a href="<?php echo e( route('signout') ); ?>" data-no-turbolink="data-no-turbolink">Sign Out</a>
                </li>
              </ul>
            </li>
            <li class="hidden-desktop">
              <a href="<?php echo e(route('notifications')); ?>">
                <i class="icon-envelope"></i>
                Notifications (<?php echo e(Auth::user()->unread_notification_count()); ?> Unread)
              </a>
            </li>
            <?php if (Auth::officer()): ?>
              <li class="dropdown notification-nav-item visible-desktop">
                <a id="notifications-dropdown-trigger" class="dropdown-toggle" data-toggle="dropdown" href="#">
                  &nbsp;
                  <i class="icon-envelope"></i>
                  <?php $count = Auth::user()->unread_notification_count() ?>
                  &nbsp;
                  <span class="badge badge-inverse unread-notification-badge <?php echo e($count == 0 ? 'hide' : ''); ?>"><?php echo e($count); ?></span>
                </a>
                <ul id="notifications-dropdown" class="dropdown-menu loading">
                  <li class="no-notifications"><?php echo e(__("r.partials.topnav.no_notifications")); ?></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>