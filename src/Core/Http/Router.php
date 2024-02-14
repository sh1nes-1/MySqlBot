<?php

namespace Sh1ne\MySqlBot\Core\Http;

use Throwable;

class Router
{

    private ExceptionHandler $exceptionHandler;

    private MiddlewareList $middlewareList;

    private array $routes = [];

    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->middlewareList = new MiddlewareList();
    }

    public function middleware(string $prefix, Middleware $middleware) : void
    {
        $this->middlewareList->add($prefix, $middleware);
    }

    public function get(string $uri, array $action) : void
    {
        $this->addAction('GET', $uri, $action);
    }

    public function post(string $uri, array $action) : void
    {
        $this->addAction('POST', $uri, $action);
    }

    private function addAction(string $method, string $uri, array $action) : void
    {
        $this->routes[$uri][$method] = $action;
    }

    public function handleRequest(Request $request) : void
    {
        $requestUri = strtok($request->uri(), '?');
        $requestMethod = $request->method();

        if (isset($this->routes[$requestUri][$requestMethod])) {
            $action = $this->routes[$requestUri][$requestMethod];

            $finalRequestHandler = new FinalRequestHandler($action);

            try {
                $response = $this->middlewareList->handle($request, $finalRequestHandler);
            } catch (Throwable $throwable) {
                $response = $this->exceptionHandler->handle($throwable);
            }

            $this->sendResponse($response);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function sendResponse(Response $response) : void
    {
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $key => $value) {
            header("$key: $value");
        }

        echo $response->getBodyAsText();
    }

}