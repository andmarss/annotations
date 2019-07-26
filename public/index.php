<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Kernel;

$data = [
    'name' => 'John',
    'surname' => 'Doe'
];
/**
 * @type Kernel
 */
(new Kernel())
    ->boot()
    ->handleRequest();