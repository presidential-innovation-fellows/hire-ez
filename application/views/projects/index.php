<?php Section::inject('page_title', 'All Projects') ?>
<ul class="projects-list">
  <?php foreach($projects as $project): ?>
    <li>
      <a class="project-title" href="<?php echo e( route('project', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
    </li>
  <?php endforeach; ?>
</ul>