<?php


namespace App;


use App\Annotations\Route;
use App\Format\FormatInterface;
use App\Format\{JSON, XML, YAML};
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class Kernel
 * @package App
 *
 * @property Container $container
 * @property array $routes
 */

class Kernel
{
    private $container;
    private $routes;

    public function __construct()
    {
       $this->container = new Container();
    }

    public function getContainer(): Container
    {
       return $this->container;
    }

    public function boot()
    {
        $this->bootContainer($this->container);

        return $this;
    }

    private function bootContainer(Container $container)
    {
        $loader = require __DIR__ . '/../vendor/autoload.php';

        $container->addService('format.json', function () use ($container){
            return new JSON();
        });

        $container->addService('format.xml', function () use ($container){
            return new XML();
        });

        $container->addService('format', function () use ($container){
            return $container->getService('format.xml');
        }, FormatInterface::class);

        $container->loadServices('App\\Service');

        AnnotationRegistry::registerLoader([$loader, 'loadClass']);
        $reader = new AnnotationReader();

        $routes = [];

        $container->loadServices(
            'App\\Controller',
            function (string $serviceName, \ReflectionClass $class) use ($reader, &$routes) {
                $route = $reader->getClassAnnotation($class, Route::class);

                if(!$route) {
                    return;
                }

                $baseRoute = $route->route;

                foreach ($class->getMethods() as $method) {
                    /**
                     * @var Route $route
                     */
                    $route = $reader->getMethodAnnotation($method, Route::class);

                    if(!$route) continue;

                    $routes[str_replace('//', '/', $baseRoute . $route->route)] = [
                        'service' => $serviceName,
                        'method' => $method->getName()
                    ];
                }
            });

        $this->routes = $routes;
    }

    public function handleRequest()
    {
       $uri = $_SERVER['REQUEST_URI'];

       if(isset($this->routes[$uri]) || isset($this->routes[$uri . '/'])) {
           $route = $this->routes[$uri] ?? $this->routes[$uri . '/'];

           $response = $this
               ->container
               ->getService($route['service'])
               ->{$route['method']}();

           echo $response;
           die;
       }
    }
}