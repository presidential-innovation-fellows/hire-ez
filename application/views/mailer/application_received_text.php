Thanks for applying for <?= __('r.app_name') ?>!

For your records, here's your application:

<?= View::make('vendors.partials.application_text')->with('vendor', $vendor) ?>


<?= __('r.email_signature_text') ?>