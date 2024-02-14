<?php

namespace Sh1ne\MySqlBot\Core\Http;

abstract class Middleware implements RequestHandler
{

    protected RequestHandler $next;

    protected ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function setNext(RequestHandler $next) : void
    {
        $this->next = $next;
    }

}