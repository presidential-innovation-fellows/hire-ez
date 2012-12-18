<?php foreach ($applicants as $bid): ?>
  <tr>
    <td>
      <a href="<?php echo e(route('vendor', $bid->vendor->id)); ?>"><?php echo Jade\Dumper::_text($bid->vendor->name) ?></a>
    </td>
    <td><?php echo Jade\Dumper::_text($bid->total_stars) ?></td>
  </tr>
<?php endforeach; ?>