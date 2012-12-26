<?php Section::inject('page_title', 'Apply for the Spring 2013 Class') ?>
<form id="new-vendor-form" action="<?php echo e(route('vendors')); ?>" method="POST">
  <?php $vendor = Input::old('vendor'); ?>
  <div class="row vendor-signup-container">
    <fieldset class="span5">
      <h5>Contact Info</h5>
      <div class="control-group">
        <label>Name</label>
        <input type="text" name="vendor[name]" value="<?php echo e($vendor['name']); ?>" />
      </div>
      <div class="control-group">
        <label>Email</label>
        <input type="text" name="vendor[email]" />
      </div>
      <div class="control-group">
        <label>Phone</label>
        <input type="text" name="vendor[phone]" />
      </div>
      <div class="control-group">
        <label>Zip</label>
        <input type="text" name="vendor[zip]" value="<?php echo e( $vendor['zip'] ); ?>" />
      </div>
    </fieldset>
    <fieldset class="span5">
      <h5>Why would you make a great fellow?</h5>
      <div class="control-group why-great-fellow">
        <textarea class="span4" name="vendor[general_paragraph]"></textarea>
        <div class="help-block pull-right">
          <code id="words-remaining">150</code>
          words left.
        </div>
        <div class="clearfix">&nbsp;</div>
      </div>
      <h5>
        Projects <br />
        <span class="smaller">check each project you are interested in serving on</span>
      </h5>
      <?php foreach($projects as $project): ?>
        <div class="project">
          <div class="control-group">
            <div class="control-label">
              <label class="checkbox">
                <div class="controls">
                  <input class="project-application-check" type="checkbox" data-projectid="<?php echo e($project->id); ?>" name="apply[project-<?php echo e($project->id); ?>]" />
                </div>
                <?php echo e($project->title); ?>
              </label>
            </div>
          </div>
          <div class="control-group why-great collapse">
            <div class="control-label">
              <label>Why are you great for this project?</label>
            </div>
            <controls>
              <textarea class="span4" data-projectid="<?php echo e($project->id); ?>" name="whygood[project-<?php echo e($project->id); ?>]"></textarea>
            </controls>
          </div>
        </div>
      <?php endforeach; ?>
    </fieldset>
  </div>
  <hr />
  <div class="resume">
    <label>
      <h5>Résumé (copy and paste)</h5>
    </label>
    <div class="wysiwyg-wrapper">
      <textarea class="wysihtml5" name="vendor[resume]"></textarea>
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-warning" type="submit">Submit Application</button>
  </div>
</form>