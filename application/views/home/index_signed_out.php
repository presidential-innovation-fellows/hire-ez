<?php Section::inject('page_title', __('r.home.index_signed_out.site_tagline')) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="row-fluid">
  <div class="span7">
    <h4><?php echo __('r.home.index_signed_out.biz_header'); ?></h4>
    <p class="main-description"><?php echo __('r.home.index_signed_out.biz_description', array('url' => route('projects'))); ?></p>
    <?php if (!Config::get('application.application_period_over')): ?>
      <a class="btn btn-warning btn-large" href="<?php echo e( route('new_vendors') ); ?>"><?php echo __('r.home.index_signed_out.biz_button'); ?></a>
    <?php else: ?>
      <p>Sorry, the application period is now over.</p>
    <?php endif; ?>
    <p class="more-info">
      For more information visit <a href="http://whitehouse.gov/innovationfellows">WhiteHouse.gov</a>
    </p>
  </div>
  <div class="span4 offset1">
    <h4><?php echo __('r.home.index_signed_out.biz_header_right'); ?></h4>
    <ul class="projects-list home-page-projects">
      <?php foreach($projects as $project): ?>
        <li>
          <a class="project-title" href="<?php echo e( route('project', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
          <span class="project-tagline"><?php echo Jade\Dumper::_text($project->tagline) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>