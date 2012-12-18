<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Admin") ?>
<?php Section::inject('active_subnav', 'admin') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
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
</div>