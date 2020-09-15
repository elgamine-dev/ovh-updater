<?php

require __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

$instance = new \App\Api($config);


$instance->routine();
