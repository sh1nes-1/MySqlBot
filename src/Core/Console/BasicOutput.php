<?php

namespace Sh1ne\MySqlBot\Core\Console;

class BasicOutput implements Output
{

    public function info(string $message) : void
    {
        $this->log("\033[0;34m", $message);
    }

    public function error(string $message) : void
    {
        $this->log("\033[0;31m", $message);
    }

    private function log(string $style, string $message) : void
    {
        $dateTime = date('Y-m-d H:i:s');

        echo "{$style}[$dateTime] $message\033[0m\n";
    }

}