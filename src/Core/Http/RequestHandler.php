<?php

namespace Sh1ne\MySqlBot\Core\Http;

interface RequestHandler
{

    public function handle(Request $request) : Response;

}