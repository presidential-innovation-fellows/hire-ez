<?php Section::inject('page_title', 'My Projects') ?>
<?php if ($projects): ?>
  <ul class="projects-list my-projects">
    <?php foreach($projects as $project): ?>
      <li>
        <a class="project-title" href="<?php echo e( route('review_bids', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
        <?php //td #{$project->status_text()} ?>
        <?php // td ?>
        <?php //a.btn.btn-mini(href="#{ route('project_admin', array($project->id)) }") Admin ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p><?php echo __("r.projects.mine.none", array("url" => route('new_projects'))); ?></p>
<?php endif; ?>