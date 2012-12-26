<p>
  Thanks for applying for <?= __('r.app_name') ?>!
</p>

<p>
  For your records, here's your application:
</p>

<pre>
  <?= View::make('vendors.partials.application_text')->with('vendor', $vendor) ?>
</pre>

<?= __('r.email_signature_html') ?>