<?php

return array(
  'connections' => array(
    'mysql' => array(
      'driver'   => 'mysql',
      'host'     => getenv('RFPEZ_DB_HOST'),
      'database' => getenv('HIREEZ_DB_NAME'),
      'username' => getenv('RFPEZ_DB_USERNAME'),
      'password' => getenv('RFPEZ_DB_PASSWORD'),
      'charset'  => 'utf8',
      'prefix'   => '',
    )
  )
);