<?php

namespace Sh1ne\MySqlBot\Core\Http;

abstract class Middleware implements RequestHandler
{

    protected RequestHandler $next;

    protected ResponseFactory $responseFactory;

    public function __construct()
    {
        $this->responseFactory = app(ResponseFactory::class);
    }

    public function setNext(RequestHandler $next) : void
    {
        $this->next = $next;
    }

}