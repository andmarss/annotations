<?php


namespace App;


class Container
{
    private $services = [];
    private $aliases = [];

    public function addService(string $name, \Closure $closure, ?string $alias = null): void
    {
       $this->services[$name] = $closure;

        if($alias) {
            $this->addAlias($alias, $name);
        }
    }

    public function addAlias(string $alias, string $service): void
    {
        $this->aliases[$alias] = $service;
    }

    public function hasService(string $name): bool
    {
       return isset($this->services[$name]);
    }

    public function hasAlias(string $name): bool
    {
       return isset($this->aliases[$name]);
    }

    public function getService(string $name)
    {
       if(!$this->hasService($name)) {
           return null;
       }

       if($this->services[$name] instanceof \Closure) {
           $this->services[$name] = $this->services[$name]();
       }

       return $this->services[$name];
    }

    public function getAlias($name)
    {
       return $this->getService($this->aliases[$name]);
    }

    public function getServices(): array
    {
        return [
            'services' => array_keys($this->services),
            'aliases' => $this->aliases
        ];
    }

    public function loadServices(string $namespace, ?\Closure $callback = null): void
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $actualDirectory = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

        $actualDirectory = $baseDir . substr($actualDirectory, strpos($actualDirectory, DIRECTORY_SEPARATOR) + 1);

        $files = array_filter(scandir($actualDirectory), function ($file) {
            return $file !== '.' && $file !== '..';
        });

        foreach ($files as $file) {
            $class = new \ReflectionClass($namespace . '\\' . basename($file, '.php'));

            $serviceName = $class->getName();

            $constructor = $class->getConstructor();
            $arguments = $constructor->getParameters();
            // Параметры для вставки в конструктор
            $serviceParameters = [];

            foreach ($arguments as $argument) {
                $type = (string) $argument->getType();

                if(!$type) die(var_dump($type));

                if($this->hasService($type) || $this->hasAlias($type)) {
                    $serviceParameters[] = $this->getService($type) ?? $this->getAlias($type);
                } else {
                    $serviceParameters[] = function () use ($type) {
                        return $this->getService($type) ?? $this->getAlias($type);
                    };
                }
            }

            $this->addService($serviceName, function () use ($serviceName, $serviceParameters){
                foreach ($serviceParameters as &$serviceParameter) {
                    if($serviceParameter instanceof \Closure) {
                        try {
                            $serviceParameter = $serviceParameter();
                        } catch (\Error $e) {
                            die(var_dump($e->getMessage()));
                        }
                    }
                }

                return new $serviceName(...$serviceParameters);
            });

            if($callback && is_callable($callback)) {
                $callback($serviceName, $class);
            }
        }
    }
}