<?php


namespace App\Controller;


use App\Service\Serializer;

class IndexController
{
    protected $serializer;

    public function __construct(Serializer $serializer)
    {
       $this->serializer = $serializer;
    }

    public function index()
    {
       return $this->serializer->serialize([
           'Action' => 'Index',
           'Time' => time()
       ]);
    }
}