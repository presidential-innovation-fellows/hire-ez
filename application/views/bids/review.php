<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review Applicants") ?>
<?php Section::inject('active_subnav', 'review_bids') ?>
<?php Section::inject('no_page_header', true) ?>
<?php Section::inject('current_page', 'bid-review') ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<ul class="nav nav-pills pull-left">
  <li class="<?php echo e(!Config::has('review_bids_filter') ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids', $project->id))); ?>">All Applicants</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'hired' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'hired')))); ?>">Hired</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'starred' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'starred')))); ?>">Starred</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'rejected' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'rejected')))); ?>">Rejected</a>
  </li>
</ul>
<form id="search-bids-form" class="form-search pull-right" action="<?php echo e(URL::full()); ?>">
  <?php echo Helper::preserve_input('sort'); ?>
  <?php echo Helper::preserve_input('order'); ?>
  <div class="input-append">
    <input class="search-query" type="text" name="q" value="<?php echo e($query); ?>" placeholder="Search Applicants" />
    <button class="btn btn-primary">Search</button>
  </div>
</form>
<div class="clearfix">&nbsp;</div>
<div class="search-subheader">
  <?php if ($query): ?>
    Filtering by "<?php echo e($query); ?>"
  <?php endif; ?>
  <small>
    <?php if ($query): ?>
      <a href="<?php echo e(Helper::current_url_without_search_params()); ?>">(clear search)</a>
    <?php endif; ?>
    <?php if (Input::get('sort')): ?>
      <a class="clear-sort" href="<?php echo e(Helper::current_url_without_sort_params()); ?>">(clear sort)</a>
    <?php endif; ?>
  </small>
</div>
<table id="bids-table" class="table">
  <thead>
    <tr>
      <th width="10%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('unread', 'Unread')) ?></th>
      <th width="60%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('name', 'Name')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('thumbsups', 'Thumbs-ups', 'desc')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('comments', 'Comments', 'desc')) ?></th>
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