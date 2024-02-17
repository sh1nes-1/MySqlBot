<?php

namespace Sh1ne\MySqlBot\Controllers;

use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Response;

class StatusController extends Controller
{

    public function index() : Response
    {
        return $this->responseFactory->json([
            'status' => 'ok',
        ]);
    }

}