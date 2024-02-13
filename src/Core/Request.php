<?php

namespace Sh1ne\MySqlBot\Core;

class Request
{

    public function uri() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get(string $key) : mixed
    {
        return $_GET[$key] ?? null;
    }

    public function header(string $key)
    {
        $headers = getallheaders();

        return $headers[$key] ?? null;
    }

    public function body() : array
    {
        return $_POST;
    }

}