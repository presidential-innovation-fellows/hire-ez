<?php Section::inject('page_title', $vendor->name) ?>
<div class="vendor-detail">
  <dl>
    <dt>Name</dt>
    <dd><?php echo Jade\Dumper::_text($vendor->name) ?></dd>
    <dt>
      Projects Applied For
      <?php foreach ($vendor->bids as $bid): ?>
        <dd><?php echo Jade\Dumper::_text($bid->project->title) ?></dd>
      <?php endforeach; ?>
    </dt>
  </dl>
</div>