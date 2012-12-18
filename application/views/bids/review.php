<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review Applicants") ?>
<?php Section::inject('active_subnav', 'review_bids') ?>
<?php Section::inject('no_page_header', true) ?>
<?php Section::inject('current_page', 'bid-review') ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<ul class="nav nav-pills pull-left">
  <li class="<?php echo e(!Config::has('review_bids_filter') ? 'active' : ''); ?>">
    <a href="<?php echo e(route('review_bids', $project->id)); ?>">All Applicants</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'hired' ? 'active' : ''); ?>">
    <a href="<?php echo e(route('review_bids_filtered', array($project->id, 'hired'))); ?>">Hired</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'starred' ? 'active' : ''); ?>">
    <a href="<?php echo e(route('review_bids_filtered', array($project->id, 'starred'))); ?>">Starred</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'rejected' ? 'active' : ''); ?>">
    <a href="<?php echo e(route('review_bids_filtered', array($project->id, 'rejected'))); ?>">Rejected</a>
  </li>
</ul>
<?php if (Input::get('sort')): ?>
  <a class="pull-right" href="<?php echo e(URL::current()); ?>">(Clear sort)</a>
<?php endif; ?>
<table id="bids-table" class="table">
  <thead>
    <tr>
      <th width="10%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('unread', 'Unread')) ?></th>
      <th width="40%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('name', 'Name')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('thumbsups', 'Thumbs-ups', 'desc')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('comments', 'Comments', 'desc')) ?></th>
      <th width="20%" colspan="3">Actions</th>
    </tr>
  </thead>
  <script type="text/javascript">
    $(function(){
     new Rfpez.Backbone.BidPage({project_id: <?php echo e($project->id); ?>, bootstrap: <?php echo $bids_json; ?>});
    })
  </script>
</table>
<div class="pagination-wrapper">
  <?php echo $links; ?>
</div>