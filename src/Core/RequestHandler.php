<?php

namespace Sh1ne\MySqlBot\Core;

interface RequestHandler
{

    public function handle(Request $request) : Response;

}