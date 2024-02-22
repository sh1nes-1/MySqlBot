<?php

namespace Sh1ne\MySqlBot\Core\ErrorHandler;

interface ErrorHandler
{

    public function setup(callable $shutdownCallback) : void;

}