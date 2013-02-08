<?php Section::inject('page_title', 'All Applicants') ?>
<?php Section::inject('no_page_header', true) ?>
<h3>
  <?php echo e(count($applicants)); ?>
  <span class="muted">candidates have applied.</span>
</h3>
<table class="table applicants-simple">
  <thead>
    <tr>
      <th>Name</th>
      <th>Location</th>
      <th>Email</th>
    </tr>
    <tbody>
      <?php foreach ($applicants as $applicant) { ?>
        <tr>
          <td>
            <a href="<?php echo e(route('vendor', array($applicant->id))); ?>"><?php echo e($applicant->name); ?></a>
          </td>
          <td><?php echo e($applicant->location); ?></td>
          <td><?php echo e($applicant->email); ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </thead>
</table>
<?php foreach ($projects as $project): ?>
  <div class="project">
    <h4>Top un-hired applicants from <?php echo e($project->title); ?></h4>
    <table class="top-applicant-table table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Total Stars</th>
        </tr>
      </thead>
      <tbody>
        <?php echo View::make('vendors.partials.applicants_trs')->with('applicants', $project->top_unhired_applicants()->take(10)->get()); ?>
      </tbody>
    </table>
    <div class="centered">
      <?php if ($project->top_unhired_applicants()->count() > 10): ?>
        <div class="btn btn-primary load-more-applicants-button" data-project-id="<?php echo e($project->id); ?>" data-current-page="2">Load More</div>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>