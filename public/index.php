<?php
require_once __DIR__.'/../vendor/autoload.php';
// use App\Format;
// use App\Format as F;
 use App\Format\{JSON,XML,YAML};
// use App\Format\JSON;
// use App\Format\XML;
// use App\Format\YAML;
// $json = new App\Format\JSON();
// $xml = new App\Format\XML();
// $yml = new App\Format\YAML();
// $json = new F\JSON();
// $xml = new F\XML();
// $yml = new F\YAML();
 $json = new JSON();
 $xml = new XML();
 $yml = new YAML();

 $class = new ReflectionClass(JSON::class);

 var_dump($class);

 $constructor = $class->getConstructor();

 var_dump($constructor);

 $parameters = $constructor->getParameters();

 var_dump($parameters);

 foreach ($parameters as $parameter) {
     $type = $parameter->getType();

     var_dump((string) $type);

     var_dump($type->isBuiltin());
     var_dump($parameter->allowsNull());
     var_dump($parameter->getDefaultValue());
 }