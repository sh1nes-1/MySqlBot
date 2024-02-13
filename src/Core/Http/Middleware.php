<?php

namespace Sh1ne\MySqlBot\Core\Http;

abstract class Middleware implements RequestHandler
{

    protected RequestHandler $next;

    public function setNext(RequestHandler $next) : void
    {
        $this->next = $next;
    }

}