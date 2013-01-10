<?php Section::inject('page_title', 'New Project') ?>
<?php Section::inject('no_page_header', true) ?>
<h4>New Project</h4>
<form id="new-project-form" action="<?php echo e(route('projects')); ?>" method="POST">
  <div class="control-group">
    <label>Project Title</label>
    <input class="readable-width" type="text" name="project[title]" />
  </div>
  <div class="control-group">
    <label>Tagline</label>
    <textarea class="readable-width" name="project[tagline]"></textarea>
  </div>
  <div class="control-group">
    <label>Body</label>
    <textarea class="readable-width" name="project[body]" style="min-height: 220px;"></textarea>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Create Project</button>
  </div>
</form>