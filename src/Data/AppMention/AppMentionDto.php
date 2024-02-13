<?php

namespace Sh1ne\MySqlBot\Data\AppMention;

class AppMentionDto
{

    /**
     * @var string $token Example: ZZZZZZWSxiZZZ2yIvs3peJ
     */
    public readonly string $token;

    public readonly string $teamId;

    public readonly string $apiAppId;

    public readonly AppMentionEventDto $event;

    /**
     * @var string $type Example: A123ABC456
     */
    public readonly string $type;

    public readonly string $eventId;

    public readonly string $eventTime;

    /**
     * @var array<string> $authedUsers
     */
    public readonly array $authedUsers;

    public function __construct(array $data)
    {
        $this->token = $data['token'];
    }

}