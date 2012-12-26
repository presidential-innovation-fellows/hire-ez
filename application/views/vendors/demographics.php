<?php Section::inject('page_title', 'Applicant Demographics') ?>
<p>
  Out of <strong><?php echo e($total); ?></strong> applicants, <strong><?php echo e($surveyed_total); ?></strong> have opted
  to take the demographic survey.
</p>
<table class="table">
  <thead>
    <tr>
      <th>Gender</th>
      <th># Applicants</th>
      <th>%</th>
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
      <th>Race</th>
      <th># Applicants</th>
      <th>%</th>
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