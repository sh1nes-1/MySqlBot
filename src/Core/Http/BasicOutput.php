<?php

namespace Sh1ne\MySqlBot\Core\Http;

class BasicOutput implements Output
{

    public function sendResponse(Response $response) : void
    {
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $key => $value) {
            header("$key: $value");
        }

        echo $response->getBodyAsText();
    }

}