<?php

namespace Sh1ne\MySqlBot\Domain\Services;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Database\DbException;
use Sh1ne\MySqlBot\Core\Database\ReadOnlyException;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Data\AppMention\AppMentionDto;
use Sh1ne\MySqlBot\Domain\Messenger\Messenger;
use Sh1ne\MySqlBot\Domain\ResultFormat\ResultFormatFactory;

class BotService
{

    private DbConnection $dbConnection;

    private Messenger $messenger;

    public function __construct(DbConnection $dbConnection, Messenger $messenger)
    {
        $this->dbConnection = $dbConnection;
        $this->messenger = $messenger;
    }

    public function processAppMention(string $text) : void
    {
        $sql = $this->extractSql($text);

        Log::info('Extracted SQL', [
            'sql' => $sql,
        ]);

        try {
            $result = $this->dbConnection->query($sql);

            $resultFormat = (new ResultFormatFactory())->make($result);

            $resultFormat->sendWithMessage($this->messenger, 'Your result is ready');
        } catch (DbException | ReadOnlyException $exception) {
            $this->messenger->sendMessage("Failed to execute SQL ```{$exception->getMessage()}```");
        }
    }

    private function extractSql(string $text) : string
    {
        $botName = AppConfig::getBotName();

        $message = str_replace(['```', "<@$botName>"], '', $text);

        return trim($message);
    }

}