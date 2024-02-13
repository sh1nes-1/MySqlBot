<?php

namespace Sh1ne\MySqlBot\Core;

class FinalRequestHandler implements RequestHandler
{

    private array $action;

    public function __construct(array $action)
    {
        $this->action = $action;
    }

    public function handle(Request $request) : Response
    {
        [$controllerClass, $controllerMethod] = $this->action;

        $controller = new $controllerClass();

        return $controller->$controllerMethod($request);
    }

}