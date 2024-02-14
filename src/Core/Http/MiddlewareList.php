<?php

namespace Sh1ne\MySqlBot\Core\Http;

class MiddlewareList
{

    private array $middlewareList = [];

    public function add(string $prefix, Middleware $middleware) : void
    {
        $this->middlewareList[] = [$prefix, $middleware];
    }

    public function handle(Request $request, RequestHandler $finalHandler) : Response
    {
        $requestHandler = $this->getRequestHandler($request, $finalHandler);

        return $requestHandler->handle($request);
    }

    private function getRequestHandler(Request $request, RequestHandler $finalHandler) : RequestHandler
    {
        $middlewareList = $this->determineMiddlewareForRequest($request);

        $nextMiddleware = $finalHandler;

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

}