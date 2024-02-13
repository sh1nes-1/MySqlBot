<?php

namespace Sh1ne\MySqlBot\Core\Http;

class Controller
{

    public function json(mixed $data) : Response
    {
        return new JsonResponse($data);
    }

}