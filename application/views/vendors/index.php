<?php Section::inject('page_title', 'All Applicants') ?>
<?php Section::inject('no_page_header', true) ?>
<p>
  <a href="<?php echo e(route('vendor_demographics')); ?>">Applicant Demographics</a>
</p>
<hr />
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