<?php

namespace Sh1ne\MySqlBot\Core\Http;

interface Output
{

    public function sendResponse(Response $response) : void;

}