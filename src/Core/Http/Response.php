<?php

namespace Sh1ne\MySqlBot\Core\Http;

interface Response
{

    public function getBodyAsText() : string;

    public function getHeaders() : array;

    public function getStatusCode() : int;

}