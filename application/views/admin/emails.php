<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('admin.partials.subnav')->with('current_page', 'emails'); ?>
<h5>Applicants</h5>
<p>
  <em>A csv list of applicant emails.</em>
</p>
<textarea class="superbig" data-select-text-on-focus="data-select-text-on-focus"><?php echo e(implode(', ', $vendor_emails)); ?></textarea>
<h5>Officers</h5>
<p>
  <em>A csv list of officers who have their email preferences set to allow emails.</em>
</p>
<textarea class="superbig" data-select-text-on-focus="data-select-text-on-focus"><?php echo e(implode(', ', $officer_emails)); ?></textarea>