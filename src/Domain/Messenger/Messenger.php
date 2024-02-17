<?php

namespace Sh1ne\MySqlBot\Domain\Messenger;

interface Messenger
{

    public function sendMessage(string $message) : void;

}