<?php

namespace Sh1ne\MySqlBot\Core\Http;

interface Request
{

    public function uri() : string;

    public function method() : string;

    public function get(string $key) : mixed;

    public function header(string $key) : ?string;

    public function rawBody() : string;

    public function body() : array;

}