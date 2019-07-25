<?php
require_once __DIR__.'/../vendor/autoload.php';

 use App\Format\{JSON,XML,YAML};

$data = [
    'name' => 'John',
    'surname' => 'Doe'
];

$seriaizer = new \App\Serializer(new YAML());

var_dump($seriaizer->serialize($data));