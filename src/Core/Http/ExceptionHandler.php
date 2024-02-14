<?php

namespace Sh1ne\MySqlBot\Core\Http;

use Throwable;

abstract class ExceptionHandler
{

    protected ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    abstract public function handle(Throwable $throwable) : Response;

}