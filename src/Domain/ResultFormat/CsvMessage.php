<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

class CsvMessage extends ResultFormat
{

    public function sendWithMessage(Messenger $messenger, string $message) : void
    {
        $csvResult = $this->convertResultToCsv();

        $messenger->sendMessage("$message ```$csvResult```");
    }

    private function convertResultToCsv() : string
    {
        $csvResult = $this->makeCsvLine($this->result->getColumns());

        foreach ($this->result->getRows() as $row) {
            $csvResult .= $this->makeCsvLine($row);
        }

        return $csvResult;
    }

    private function makeCsvLine(mixed $row) : string
    {
        return implode(',', $row) . "\n";
    }

}