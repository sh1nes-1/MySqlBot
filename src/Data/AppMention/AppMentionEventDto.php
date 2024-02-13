<?php

namespace Sh1ne\MySqlBot\Data\AppMention;

class AppMentionEventDto
{

    public readonly string $type;

    public readonly string $user;

    public readonly string $text;

    public readonly string $ts;

    public readonly string $channel;

    public readonly string $eventTs;

    public function __construct(array $data)
    {
        // TODO
    }

}