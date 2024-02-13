<?php

namespace Sh1ne\MySqlBot\Core;

use Throwable;

interface ExceptionHandler
{

    public function handle(Throwable $throwable) : Response;

}