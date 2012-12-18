<?php $unread = Auth::user()->unread_notification_for_payload("bid", $bid->id) ?>
<tbody class="bid <?php echo e($unread ? 'unread' : ''); ?>" data-project-id="<?php echo e($bid->project->id); ?>" data-bid-id="<?php echo e($bid->id); ?>" data-vendor-company-name="<?php echo e($bid->vendor->name); ?>" data-vendor-email="<?php echo e($bid->vendor->user->email); ?>">
  <tr>
    <td class="bid-notification-td">
      <a class="btn btn-small btn-primary btn-circle mark-as-read">&nbsp;</a>
      <a class="btn btn-small btn-circle mark-as-unread">&nbsp;</a>
    </td>
    <td class="star-td <?php echo e($bid->starred ? 'starred' : ''); ?>">
      <a class="btn btn-inverse btn-mini unstar-button">
        <i class="icon-star"></i>
      </a>
      <a class="btn btn-mini star-button">
        <i class="icon-star-empty"></i>
      </a>
    </td>
    <td class="bid-vendor-td">
      <a data-toggle="collapse" data-target="#bid<?php echo e($bid->id); ?>"><?php echo e($bid->vendor->name); ?></a>
      <?php if ($bid->awarded_at): ?>
        <span class="label label-success">Winning Applicant!</span>
      <?php endif; ?>
    </td>
    <td>
      <?php if (!$bid->awarded_at): ?>
        <?php if ($bid->dismissed()): ?>
          <a class="btn btn-info undismiss-button" data-move-to-table="true">Un-reject</a>
          <div>
            <em>Dismissed</em>
          </div>
        <?php else: ?>
          <a class="btn btn-warning show-dismiss-modal" data-move-to-table="true">Reject</a>
          <?php if (!$bid->project->winning_bid()): ?>
            <a class="btn btn-primary show-award-modal" data-move-to-table="true">Hire</a>
          <?php endif; ?>
        <?php endif; ?>
      <?php else: ?>
        <?php echo __('r.bids.partials.bid_for_review.congrats'); ?>
      <?php endif; ?>
    </td>
  </tr>
</tbody>