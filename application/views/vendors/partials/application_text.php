## Basic Info

**Name:** <?= $vendor->name ?>

**Email:** <?= $vendor->email ?>

**Phone:** <?= $vendor->phone ?>

**Zip:** <?= $vendor->zip ?>

### Why would you make a great fellow?

<?= $vendor->general_paragraph ?>

## Projects

<?php foreach($vendor->bids as $bid): ?>
### <?= $bid->project->title ?>

<?= $bid->body ?>

<?php endforeach; ?>

## Resume

<?= $vendor->resume ?>