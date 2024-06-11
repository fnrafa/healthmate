<?php

namespace App\Providers;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Route
{
    protected static array $routes = [];
    protected static array $namedRoutes = [];

    public static function get($uri, $action, $name = null): void
    {
        self::$routes['GET'][$uri] = $action;
        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }

    public static function post($uri, $action, $name = null): void
    {
        self::$routes['POST'][$uri] = $action;
        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }

    public static function view($uri, $view, $data = [], $name = null): void
    {
        self::$routes['GET'][$uri] = function () use ($view, $data) {
            return view($view, $data);
        };
        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }

    public static function dispatch(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach (self::$routes as $method => $routes) {
                foreach ($routes as $uri => $action) {
                    $r->addRoute($method, $uri, $action);
                }
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo view('errors.404');
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo view('errors.405');
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $request = new Request();
                if (is_callable($handler)) {
                    echo call_user_func($handler, $request);
                } elseif (is_array($handler)) {
                    list($class, $method) = $handler;
                    echo call_user_func_array([new $class, $method], array_merge([$request], $vars));
                }
                break;
        }
    }

    public static function route($name)
    {
        return self::$namedRoutes[$name] ?? null;
    }
}

function route($name)
{
    return Route::route($name);
}
