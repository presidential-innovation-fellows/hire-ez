<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('active_subnav', "view") ?>
<?php Section::inject('no_page_header', true) ?>
<?php if ($project->is_mine()): ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
  <?php echo View::make('projects.partials.answer_question_form'); ?>
<?php else: ?>
  <p>
    <a href="<?php echo e(route('root')); ?>">&larr; Back home</a>
  </p>
  <h4><?php echo Jade\Dumper::_text($project->title) ?></h4>
<?php endif; ?>
<div class="row">
  <div class="main-description span6">
    <?php echo $project->body; ?>
  </div>
  <div class="span4 offset1">
    <a class="btn btn-warning" href="<?php echo e( route('new_vendors') ); ?>">Apply Now!</a>
  </div>
</div>