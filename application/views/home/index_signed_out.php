<?php Section::inject('page_title', __('r.home.index_signed_out.site_tagline')) ?>
<?php Section::inject('no_page_header', true) ?>
<?php //.hero-unit ?>
<?php //h1 #{__('r.app_name')} ?>
<?php //small #{__('r.home.index_signed_out.site_tagline')} ?>
<div class="row-fluid">
  <div class="span5">
    <h4><?php echo __('r.home.index_signed_out.biz_header'); ?></h4>
    <p class="main-description"><?php echo __('r.home.index_signed_out.biz_description', array('url' => route('projects'))); ?></p>
    <a class="btn btn-warning btn-large" href="<?php echo e( route('new_vendors') ); ?>"><?php echo __('r.home.index_signed_out.biz_button'); ?></a>
  </div>
  <div class="span6 offset1">
    <h4><?php echo __('r.home.index_signed_out.biz_header_right'); ?></h4>
    <ul class="projects-list home-page-projects">
      <?php foreach($projects as $project): ?>
        <li>
          <a class="project-title" href="<?php echo e( route('project', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>