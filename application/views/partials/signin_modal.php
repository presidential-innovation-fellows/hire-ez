<div id="signinModal" class="modal hide" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button class="close" type="button" data-dismiss="modal">&times;</button>
    <h5>Login to <?php echo e(__('r.app_name')); ?></h5>
  </div>
  <div class="modal-body">
    <form action="<?php echo e(route('signin')); ?>" method="POST">
      <input type="hidden" name="modal" value="true" />
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
          <input id="email" class="span3" type="text" name="email" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Password</label>
        <div class="controls">
          <input class="span3" type="password" name="password" />
          <a class="forgot" href="<?php echo e(route('forgot_password')); ?>">Forgot Password?</a>
        </div>
      </div>
      <p>
        <button class="btn btn-warning" type="submit">Sign in</button>
      </p>
    </form>
  </div>
</div>