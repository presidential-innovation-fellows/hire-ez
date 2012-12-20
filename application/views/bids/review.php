<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review Applicants") ?>
<?php Section::inject('active_subnav', 'review_bids') ?>
<?php Section::inject('no_page_header', true) ?>
<?php Section::inject('current_page', 'bid-review') ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<?php echo View::make('bids.partials.keyboard_shortcuts_modal'); ?>
<ul class="nav nav-pills pull-left">
  <li class="<?php echo e(Config::get('review_bids_filter') == 'unread' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'unread')))); ?>">Unread (<?php echo e($project->unread_bids()->count()); ?>)</a>
  </li>
  <li class="<?php echo e(!Config::has('review_bids_filter') ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids', $project->id))); ?>">All (<?php echo e($project->submitted_bids()->count()); ?>)</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'starred' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'starred')))); ?>">My <i class="icon-thumbs-up"></i> (<?php echo e($project->starred_bids()->count()); ?>)</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'thumbs-downed' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'thumbs-downed')))); ?>">My <i class="icon-thumbs-down"></i> (<?php echo e($project->thumbs_downed_bids()->count()); ?>)</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'hired' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'hired')))); ?>">Hired (<?php echo e($project->winning_bids()->count()); ?>)</a>
  </li>
  <li class="<?php echo e(Config::get('review_bids_filter') == 'spam' ? 'active' : ''); ?>">
    <a href="<?php echo e(Helper::url_with_query_and_sort_params(route('review_bids_filtered', array($project->id, 'spam')))); ?>">Spam (<?php echo e($project->dismissed_bids()->count()); ?>)</a>
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
  <div id="bid-review-pagination-wrapper" class="pull-right" data-href="<?php echo e(URL::current()); ?>" data-filter="<?php echo e(Config::get('review_bids_filter')); ?>" data-skip="<?php echo e($skip); ?>" data-sort="<?php echo e($sort); ?>" data-query="<?php echo e($query); ?>" data-total="<?php echo e($paginator['total']); ?>">
    <div class="pagination pagination-right">
      <span class="pagination-text">
        <small>
          <a href="#keyboard-shortcuts-modal" data-toggle="modal">Keyboard shortcuts available<i class="icon-thumbs-up" style="margin-left: 3px;"></i></a>
        </small>
        &nbsp;&nbsp;
        <strong><?php echo e($paginator["display_range"]); ?></strong> of <strong><?php echo e($paginator["total"]); ?></strong>
      </span>
      <ul>
        <li class="<?php echo e($paginator['showingFirstResult'] ? 'disabled' : ''); ?>">
          <a class="previous">«</a>
        </li>
        <li class="<?php echo e($paginator['showingLastResult'] ? 'disabled' : ''); ?>">
          <a class="next">»</a>
        </li>
      </ul>
    </div>
  </div>
</div>
<table id="bids-table" class="table">
  <thead>
    <tr>
      <th width="10%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('unread', 'Unread')) ?></th>
      <th width="40%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('name', 'Name')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('score', 'Score', 'desc')) ?></th>
      <th width="15%"><?php echo Jade\Dumper::_html(Helper::current_sort_link('comments', 'Comments', 'desc')) ?></th>
      <th width="20%">Actions</th>
    </tr>
  </thead>
  <script type="text/javascript">
    $(function(){
     new Rfpez.Backbone.BidPage({project_id: <?php echo e($project->id); ?>, bootstrap: <?php echo $bids_json; ?>});
    })
  </script>
</table>