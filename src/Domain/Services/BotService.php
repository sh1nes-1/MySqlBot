<?php

namespace Sh1ne\MySqlBot\Domain\Services;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\DbConnection;
use Sh1ne\MySqlBot\Domain\Data\AppMention\AppMentionDto;
use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

class BotService
{

    private DbConnection $dbConnection;

    private Messenger $messenger;

    public function __construct(DbConnection $dbConnection, Messenger $messenger)
    {
        $this->dbConnection = $dbConnection;
        $this->messenger = $messenger;
    }

    public function processAppMention(AppMentionDto $appMentionDto) : void
    {
        $sql = $this->extractSql($appMentionDto);

        $result = $this->dbConnection->query($sql);

        $csvResult = $this->convertToCsv($result);

        $this->messenger->sendMessage("Your result is ready\n$csvResult");
    }

    private function extractSql(AppMentionDto $appMentionDto) : string
    {
        $botName = AppConfig::getBotName();

        $message = str_replace("<@$botName>", '', $appMentionDto->event->text);

        return trim($message);
    }

    private function convertToCsv(array $result) : string
    {
        $csvResult = '';

        foreach ($result as $row) {
            $csvResult .= implode(',', $row) . "\n";
        }

        return $csvResult;
    }

}