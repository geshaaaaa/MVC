<?php

namespace Core;


use App\Controllers\BaseApiController;
use App\Enums\Http\Status;
use Core\Traits\RouteHttpMethods;
use Exception;



class Router
{
    use RouteHttpMethods;

    static protected ?Router $instance = null;
    protected array $routes = [];
    protected array $params = [];
    protected string $currentRoute;
    protected array $converTypes = [
        'd' => 'int',
        '.' => 'string'
    ];

    /**
     * @return Router|null
     */
    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    static protected function setUri(string $uri): static
    {
        $uri = preg_replace('/\//', '\\/', $uri);

        $uri = preg_replace('/\{([a-zA-Z_-]+):([^}]+)}/', '(?P<$1>$2)', $uri);

        $uri = "/^$uri$/i";

        $router = static::getInstance();

        $router->routes[$uri] = [];

        $router->currentRoute = $uri;

        return $router;
    }

    protected function removeQueryVariables(string $uri): string
    {
        return preg_replace('/([\w\/\d]+)(\?[\w=\d\&\%\[\]\-\_\:\+\"\"\'\']+)/i', '$1', $uri);
    }

    protected function match(string $uri): bool
    {
        foreach ($this->routes as $regex => $params) {
            if (preg_match($regex, $uri, $matches)) {

                $this->params = $this->buildParams($regex, $matches, $params);
                return true;
            }
        }

        throw new Exception(__CLASS__ . ": Route [$uri] not found", 404);
    }


    protected function buildParams(string $regex, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>\\\\?([\w\.][\+]*)\)/', $regex, $types);
        if ($types) {
            $uriParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $lastKey = array_key_last($types);
            $step = 0;
            $types = array_map(
                fn($value) => str_replace('+', "", $value), $types[$lastKey]
            );

            foreach ($uriParams as $key => $value) {
                settype($value, $this->converTypes[$types[$step]]);
                $params[$key] = $value;
                $step++;
            }

        }
        return $params;
    }


    public function controller($controller): static
    {
        if (!class_exists($controller)) {
            throw new Exception("Class $controller doesnt exist");
        }

        if (!in_array(get_parent_class($controller), [Controller::class, BaseApiController::class])) {
            throw new Exception("$controller doesnt extend class Controller");
        }
        $this->routes[$this->currentRoute]["controller"] = $controller;
        return $this;

    }

    public function actions($action): void
    {

        if (empty($this->routes[$this->currentRoute]['controller'])) {
            throw new Exception("Controller null or doesnt exist");
        }
        $controller = $this->routes[$this->currentRoute]["controller"];

        if (!method_exists($controller, $action)) {
            throw new Exception("Method $action doesnt exist");
        }
        $this->routes[$this->currentRoute]["action"] = $action;

    }

    protected function makeJson($data): string
    {
        header_remove();
        header("Content-Type: application/json");

        return json_encode($data);

    }
    protected function checkHtttpMethod() : void
    {
        $requestMethod = ($_SERVER['REQUEST_METHOD']);

        if ($requestMethod !== $this->params['method'])
            {
                throw new Exception("Requested method {$requestMethod} does not allow");

            }
        unset($this->params['method']);
    }

    static public function dispatch($uri)
    {
        $router = static::getInstance();
        $uri = $router->removeQueryVariables($uri);
        $uri = trim($uri, "/");

        if ($router->match($uri)) {
            $router->checkHtttpMethod();

            $controller = new $router->params['controller'];
            $actions = $router->params['action'];

           unset($router->params['controller']);
           unset($router->params['action']);

            if ($controller->before($actions, $router->params)) {
                $response = call_user_func_array([$controller, $actions], $router->params);

                $controller->after($actions, $response);

                return jsonResponse(
                    $response['status'],
                    [
                        'data' => $response['body'],
                        'errors' => $response['errors']
                    ]
                );
            }
        }

        return jsonResponse(
            Status::INTERNAL_SERVER_ERROR,
            [
                'data' => [],
                'errors' => 'Internal Server Error'
            ]
        );
    }
}