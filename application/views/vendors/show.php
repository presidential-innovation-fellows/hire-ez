<?php Section::inject('page_title', $vendor->name) ?>
<div class="vendor-detail row-fluid">
  <div class="span6">
    <h5>Applicant Info</h5>
    <dl>
      <?php echo Helper::datum('Name', $vendor->name); ?>
      <?php echo Helper::datum('Email', $vendor->email, true); ?>
      <?php echo Helper::datum('Phone', $vendor->phone); ?>
      <?php echo Helper::datum('Location', $vendor->location); ?>
      <?php echo Helper::datum('Link 1', $vendor->link_1, true); ?>
      <?php echo Helper::datum('Link 2', $vendor->link_2, true); ?>
      <?php echo Helper::datum('Link 3', $vendor->link_3, true); ?>
    </dl>
  </div>
  <div class="span6">
    <h5>Projects Applied For</h5>
    <ul>
      <?php foreach ($vendor->bids as $bid): ?>
        <li>
          <?php if ($bid->project->is_mine()): ?>
            <a href="<?php echo e(route('bid', array($bid->project->id, $bid->id))); ?>"><?php echo Jade\Dumper::_text($bid->project->title) ?></a>
          <?php else: ?>
            <?php echo e($bid->project->title); ?>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <h5>Comments</h5>
    <div id="vendor-comments"></div>
    <script type="text/javascript">
      $(function(){
        var commentView = new Rfpez.Backbone.BidCommentsView({vendor_id: <?php echo e($vendor->id); ?>});
        $("#vendor-comments").html(commentView.el)
      });
    </script>
  </div>
</div>
<h5>General Paragraph
</h5>
<?php echo nl2br(e($vendor->general_paragraph)); ?>
<h5>Resume
</h5>
<?php echo $vendor->resume_safe(); ?>