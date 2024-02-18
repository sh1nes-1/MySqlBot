<?php

namespace Sh1ne\MySqlBot\Domain\Services;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Database\DbException;
use Sh1ne\MySqlBot\Core\Database\ReadOnlyException;
use Sh1ne\MySqlBot\Core\Log;
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

        Log::info('Extracted SQL', [
            'sql' => $sql,
        ]);

        try {
            $result = $this->dbConnection->query($sql);

            $csvResult = $this->convertToCsv($result);

            $this->messenger->sendMessage("Your result is ready ```$csvResult```");
        } catch (DbException | ReadOnlyException $exception) {
            $this->messenger->sendMessage("Failed to execute SQL ```{$exception->getMessage()}```");
        }
    }

    private function extractSql(AppMentionDto $appMentionDto) : string
    {
        $botName = AppConfig::getBotName();

        $message = str_replace(['```', "<@$botName>"], '', $appMentionDto->event->text);

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