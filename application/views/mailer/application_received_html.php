<p>Thanks for applying for <?= __('r.app_name') ?>! We have received your application, and it is will be reviewed.</p>

<p>In terms of what to expect -- the application window closes on March 17th and due to the volume of applications 
we're receiving, you likely won't hear from us until April. If an individual agency believes there might be a good 
fit, officials will reach out to you to arrange for an interview. In the meantime, please continue to track program 
updates through your email inbox or on Twitter via @WhiteHouseOSTP.</p>

<p>Your application means a lot. Thank you again for being willing to use your talents and skills to better your
government and your country.</p>

<p>For your records, here's your application:</p>

<pre>
  <?= View::make('vendors.partials.application_text')->with('vendor', $vendor) ?>
</pre>

<?= __('r.email_signature_html') ?>
