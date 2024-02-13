<?php

namespace Sh1ne\MySqlBot\Core;

use Throwable;

class Router
{

    private ExceptionHandler $exceptionHandler;

    private array $routes = [];

    private array $middlewareList = [];

    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    public function middleware(string $prefix, Middleware $middleware) : void
    {
        $this->middlewareList[] = [$prefix, $middleware];
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

            $requestHandler = $this->getRequestHandler($request, $action);

            try {
                $response = $requestHandler->handle($request);
            } catch (Throwable $throwable) {
                $response = $this->exceptionHandler->handle($throwable);
            }

            $this->sendResponse($response);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function getRequestHandler(Request $request, mixed $action) : RequestHandler
    {
        $middlewareList = $this->determineMiddlewareForRequest($request);

        $nextMiddleware = new FinalRequestHandler($action);

        for ($i = count($middlewareList) - 1; $i >= 0; $i--) {
            $middlewareList[$i]->setNext($nextMiddleware);

            $nextMiddleware = $middlewareList[$i];
        }

        return $nextMiddleware;
    }

    /**
     * @return array<Middleware>
     */
    private function determineMiddlewareForRequest(Request $request) : array
    {
        $result = [];

        foreach ($this->middlewareList as [$prefix, $middleware]) {
            if (str_starts_with($request->uri(), $prefix)) {
                $result[] = $middleware;
            }
        }

        return $result;
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