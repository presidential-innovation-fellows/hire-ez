<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('active_subnav', "view") ?>
<?php Section::inject('no_page_header', true) ?>
<p>
  <a href="<?php echo e(route('root')); ?>">&larr; back to application</a>
</p>
<h4><?php echo Jade\Dumper::_text($project->title) ?></h4>
<div class="row">
  <div class="main-description span6">
    <?php echo nl2br($project->body); ?>
  </div>
  <div class="span4 offset1">
    <a class="btn btn-warning" href="<?php echo e( route('new_vendors') ); ?>">Apply Now!</a>
  </div>
</div>