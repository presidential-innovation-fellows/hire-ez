<?php Section::inject('page_title', 'Apply for the Spring 2013 Class') ?>
<?php Section::inject('current_page', 'new-vendor') ?>
<form id="new-vendor-form" action="<?php echo e(route('vendors')); ?>" method="POST">
  <?php $vendor = Input::old('vendor'); ?>
  <?php $project_application = Input::old("project_application") ?: array(); ?>
  <div class="row vendor-signup-container">
    <fieldset class="span5">
      <h5>Contact Info</h5>
      <div class="control-group">
        <label>Name</label>
        <input type="text" name="vendor[name]" value="<?php echo e($vendor['name']); ?>" />
      </div>
      <div class="control-group">
        <label>Email</label>
        <input type="text" name="vendor[email]" value="<?php echo e($vendor['email']); ?>" />
      </div>
      <div class="control-group">
        <label>Phone</label>
        <input type="text" name="vendor[phone]" value="<?php echo e($vendor['phone']); ?>" />
      </div>
      <div class="control-group">
        <label>Location</label>
        <input id="locationInput" type="text" name="vendor[location]" value="<?php echo e( $vendor['location'] ); ?>" />
        <input id="latitudeInput" type="hidden" name="vendor[latitude]" value="<?php echo e( $vendor['latitude'] ); ?>" />
        <input id="longitudeInput" type="hidden" name="vendor[longitude]" value="<?php echo e( $vendor['longitude'] ); ?>" />
      </div>
      <h5>
        Where can we find you online?<br />
        <span class="smaller">Add up to 3 links (e.g. GitHub, LinkedIn, etc). Optional.</span>
      </h5>
      <div class="control-group">
        <label>Link #1</label>
        <input type="text" name="vendor[link_1]" value="<?php echo e($vendor['link_1']); ?>" />
      </div>
      <div class="control-group">
        <label>Link #2</label>
        <input type="text" name="vendor[link_2]" value="<?php echo e($vendor['link_2']); ?>" />
      </div>
      <div class="control-group">
        <label>Link #3</label>
        <input type="text" name="vendor[link_3]" value="<?php echo e($vendor['link_3']); ?>" />
      </div>
    </fieldset>
    <fieldset class="span5">
      <h5>Why would you make a great fellow?</h5>
      <div class="control-group why-great-fellow">
        <textarea class="span4" name="vendor[general_paragraph]"><?php echo e($vendor['general_paragraph']); ?></textarea>
        <div class="help-block pull-right">
          <code id="words-remaining">150</code>
          words left.
        </div>
        <div class="clearfix">&nbsp;</div>
      </div>
      <h5>
        Projects <br />
        <span class="smaller">Check each project you are interested in serving on.</span>
      </h5>
      <?php foreach($projects as $project): ?>
        <div class="project">
          <div class="control-group">
            <div class="control-label">
              <label class="checkbox">
                <div class="controls">
                  <input class="project-application-check" type="checkbox" />
                </div>
                <?php echo e($project->title); ?> &nbsp;
                <a class="project-description-link" href="<?php echo e(route('project', $project->id)); ?>" target="_blank">Learn More</a>
              </label>
            </div>
          </div>
          <div class="control-group why-great collapse">
            <div class="control-label">
              <label>Why are you great for this project?</label>
            </div>
            <controls>
              <textarea class="span4" name="project_application[<?php echo e($project->id); ?>]"><?php echo e(@$project_application[$project->id]); ?></textarea>
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
      <textarea class="wysihtml5" name="vendor[resume]"><?php echo $vendor['resume']; ?></textarea>
    </div>
  </div>
  <div class="control-group">
    <label class="checkbox">
      <input type='checkbox' name="vendor[hire_me_elsewhere]" <?php if (isset($vendor["hire_me_elsewhere"])){ ?>checked="true"<?php } ?> />
      Yes, it’s OK to consider me for positions in government other than a Presidential Innovation Fellow.
    </label>
  </div>
  <div class="form-actions">
    <p class="smaller">
      <em>Information submitted through this Presidential Innovation Fellows application will be shared with Federal agencies for the purpose of candidate review, selection, and hiring.</em>
    </p>
    <button class="btn btn-warning" type="submit">Submit Application</button>
  </div>
</form>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;async=2&amp;callback=Rfpez.initialize_google_autocomplete"></script>