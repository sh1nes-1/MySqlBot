<?php

namespace Sh1ne\MySqlBot\Domain\Services;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Database\DbException;
use Sh1ne\MySqlBot\Core\Database\ReadOnlyException;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Domain\Data\AppMention\AppMentionDto;
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

    public function processAppMention(AppMentionDto $appMentionDto) : void
    {
        $sql = $this->extractSql($appMentionDto);

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

    private function extractSql(AppMentionDto $appMentionDto) : string
    {
        $botName = AppConfig::getBotName();

        $message = str_replace(['```', "<@$botName>"], '', $appMentionDto->event->text);

        return trim($message);
    }

}