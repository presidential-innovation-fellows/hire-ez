<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', $bid->vendor->name) ?>
<?php Section::inject('active_subnav', 'review_bids') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<a href="<?php echo e(route('review_bids', $project->id)); ?>">&larr; Back to list</a>
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
     new Rfpez.Backbone.BidPage({project_id: <?php echo e($project->id); ?>, bootstrap: <?php echo $bid_json; ?>, expanded: true});
    })
  </script>
</table>