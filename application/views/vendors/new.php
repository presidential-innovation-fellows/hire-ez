<?php Section::inject('page_title', 'Apply for the Spring 2013 Class') ?>
<form id="new-vendor-form" action="<?php echo e(route('vendors')); ?>" method="POST">
  <?php echo View::make('users.account_vendor_fields')->with('vendor', Input::old('vendor'))->with('user', Input::old('user'))->with('signup', true)->with('projects', $projects); ?>
  <div class="form-actions">
    <button class="btn btn-warning" type="submit">Submit Application</button>
  </div>
</form>