<?php

namespace Sh1ne\MySqlBot\Core\Http;

use Throwable;

interface ExceptionHandler
{

    public function handle(Throwable $throwable) : Response;

}