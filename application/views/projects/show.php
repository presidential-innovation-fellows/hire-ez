<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('active_subnav', "view") ?>
<?php if ($project->is_mine()): ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
  <?php echo View::make('projects.partials.answer_question_form'); ?>
<?php endif; ?>
<div class="row">
  <div class="main-description span6">
    <?php echo $project->body; ?>
  </div>
  <div class="span4 offset1">
    <a class="btn btn-warning" href="<?php echo e( route('new_vendors') ); ?>">Apply Now!</a>
  </div>
</div>