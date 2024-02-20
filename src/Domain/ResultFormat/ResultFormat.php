<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

interface ResultFormat
{

    public function sendWithMessage(Messenger $messenger, string $message) : void;

}