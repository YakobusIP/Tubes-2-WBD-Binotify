<?php

namespace Server\Router;

class Router 
{
    private array $handlers;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method.$path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
        ];
    }

    public function run()
    {
        $requstUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requstUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        foreach ($this->handlers as $handler) {
            if($handler['path'] === $requestPath && $method === $handler['method']) {
                $callback = $handler['handler'];
            }
        }

        if(is_string($callback)) {
            $parts = explode('@', $callback);
            if(is_array($parts)) {
                    $className = array_shift($parts);
                    $handler = new $className;
                
                    $method = array_shift($parts);
                    $callback = [$handler, $method];
                }
        }
    
        if(!$callback) {
            include 'Client/pages/Errors/NotFound.php';
            return;
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }
}
