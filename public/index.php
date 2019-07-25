<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Format\{JSON,XML,YAML};
use \App\Controller\IndexController;
use \App\Container;
use \App\Serializer;

$data = [
    'name' => 'John',
    'surname' => 'Doe'
];

$seriaizer = new Serializer(new XML());

$controller = new IndexController($seriaizer);

$container = new Container();

$container->addService('format.json', function () use ($container){
    return new JSON();
});

$container->addService('format.xml', function () use ($container){
    return new XML();
});

$container->addService('format', function () use ($container){
    $container->getService('format.json');
});

$container->addService('serializer', function () use ($container){
    return new Serializer($container->getService('format'));
});

$container->addService('controller.index', function () use ($container){
    return new IndexController($container->getService('serializer'));
});

var_dump($container);