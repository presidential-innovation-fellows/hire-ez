<?php Section::inject('page_title', 'Applicant Demographics') ?>
<?php Section::inject('current_page', 'demographic-stats') ?>
<?php if ($surveyed_total < 1): ?>
  <p>No applicants have opted to take the demographic survey yet.</p>
<?php else: ?>
  <p>
    Out of <strong><?php echo e($total); ?></strong> applicants, <strong><?php echo e($surveyed_total); ?></strong> have opted
    to take the demographic survey.
  </p>
  <div class="demographic-charts row">
    <div id="gender-chart" class="span-4"></div>
    <div id="race-chart" class="span-6"></div>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th width="50%">Gender</th>
        <th width="25%"># Applicants</th>
        <th width="25%">%</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Male</td>
        <td><?php echo e($male); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($male / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>Female</td>
        <td><?php echo e($female); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($female / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>Other</td>
        <td><?php echo e($other); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($other / $surveyed_total)) ?></td>
      </tr>
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th width="50%">Race</th>
        <th width="25%"># Applicants</th>
        <th width="25%">%</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Hispanic or Latino</td>
        <td><?php echo e($hispanic_latino); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($hispanic_latino / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>White</td>
        <td><?php echo e($white); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($white / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>Black or African American</td>
        <td><?php echo e($black); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($black / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>Native Hawaiian or Other Pacific Islander</td>
        <td><?php echo e($pacific_islander); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($pacific_islander / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>Asian (Not Hispanic or Latino)</td>
        <td><?php echo e($asian); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($asian / $surveyed_total)) ?></td>
      </tr>
      <tr>
        <td>American Indian or Alaska Native</td>
        <td><?php echo e($american_indian); ?></td>
        <td><?php echo Jade\Dumper::_text(Helper::demographic_percentage($american_indian / $surveyed_total)) ?></td>
      </tr>
    </tbody>
  </table>
  <?php echo HTML::script('js/vendor/raphael-min.js'); ?>
  <?php echo HTML::script('js/vendor/g.raphael-min.js'); ?>
  <?php echo HTML::script('js/vendor/g.bar-min.js'); ?>
  <?php echo HTML::script('js/vendor/g.pie-min.js'); ?>
  <script>
    window.demographicStats = {gender: [<?php echo $female; ?>, <?php echo $male; ?>, <?php echo $other; ?>], race: [<?php echo $asian; ?>,<?php echo $american_indian; ?>,<?php echo $black; ?>,<?php echo $hispanic_latino; ?>,<?php echo $pacific_islander; ?>,<?php echo $white; ?>], raceLabels: ['Asian: <?php echo $asian; ?>','American Indian: <?php echo $american_indian; ?>','Black: <?php echo $black; ?>', 'Hispanic Latino: <?php echo $hispanic_latino; ?>','Pacific Islander: <?php echo $pacific_islander; ?>','White: <?php echo $white; ?>']}
  </script>
<?php endif; ?>