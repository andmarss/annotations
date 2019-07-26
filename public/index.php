<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\{Kernel, Container};

$data = [
    'name' => 'John',
    'surname' => 'Doe'
];
/**
 * @var Kernel $kernel
 */
$kernel = new Kernel();

$kernel->boot();
/**
 * @var Container $container
 */
$container = $kernel->getContainer();

var_dump($container->getServices());
var_dump($container->getService('App\\Controller\\IndexController')->index());
var_dump($container->getService('App\\Controller\\PostController')->index());