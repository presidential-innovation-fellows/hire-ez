<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Admin") ?>
<?php Section::inject('active_subnav', 'admin') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span6">
    <h5>Update project</h5>
    <form id="update-project-form" action="<?php echo e(route('project', array($project->id))); ?>" method="POST">
      <input type="hidden" name="_method" value="PUT" />
      <div class="control-group">
        <label>Project Title</label>
        <input class="span12" type="text" name="project[title]" value="<?php echo e($project->title); ?>" />
      </div>
      <div class="control-group">
        <label>Tagline</label>
        <textarea class="textarea-full-width" name="project[tagline]"><?php echo Jade\Dumper::_text($project->tagline) ?></textarea>
      </div>
      <div class="control-group">
        <label>Body</label>
        <textarea class="textarea-full-width" name="project[body]" style="min-height: 120px;"><?php echo Jade\Dumper::_text($project->body) ?></textarea>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="span6">
    <h5>Collaborators</h5>
    <p><?php echo e(__("r.projects.admin.collaborators")); ?></p>
    <table class="table collaborators-table" data-project-id="<?php echo e($project->id); ?>">
      <thead>
        <tr>
          <th>Email</th>
          <th>Owner</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="collaborators-tbody">
        <script type="text/javascript">
          $(function(){
           new Rfpez.Backbone.CollaboratorPage({project_id: <?php echo e($project->id); ?>, owner_id: <?php echo $project->owner()->user->id; ?>, bootstrap: <?php echo $collaborators_json; ?>});
          })
        </script>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">
            <form id="add-collaborator-form" action="<?php echo e(route('project_collaborators', array($project->id))); ?>" method="POST">
              <div class="input-append">
                <input type="text" name="email" placeholder="Email Address" autocomplete="off" />
                <button class="btn btn-success">Add</button>
              </div>
            </form>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <div class="span6">
    <h5>Done hiring?</h5>
    <p>
      Once you know who you're hiring, you can help out the other projects by "releasing" your unhired applicants to the pool.
      This lets the other projects see if you had any talented applicants that could be right for them, too.
    </p>
    <form action="<?php echo e(route('project_release_applicants', $project->id)); ?>" method="POST">
      <div class="form-actions">
        <?php if (!$project->released_applicants_at): ?>
          <button class="btn btn-primary btn-xlarge">Release Unhired Applicants to Applicant Pool</button>
        <?php else: ?>
          <p>Thanks for releasing your applicants!</p>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>