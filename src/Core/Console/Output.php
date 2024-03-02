<?php

namespace Sh1ne\MySqlBot\Core\Console;

interface Output
{

    public function info(string $message) : void;

    public function error(string $message) : void;

}