<?php

namespace Sh1ne\MySqlBot\Core\Http;

class Controller
{

    protected ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

}