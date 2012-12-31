Your bid on <?= e($bid->project->title) ?> has won <?= route('bid', array($bid->project->id, $bid->id)) ?>

"<?= e($bid["awarded_message"]) ?>"

<?= __('r.email_signature_text') ?>