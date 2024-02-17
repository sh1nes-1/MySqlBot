<?php

namespace Sh1ne\MySqlBot\Data\AppMention;

/**
 * @link https://api.slack.com/events/app_mention
 */
class AppMentionDto
{

    /**
     * @var string $token Example: ZZZZZZWSxiZZZ2yIvs3peJ
     */
    public readonly string $token;

    /**
     * @var string $teamId Example: T123ABC456
     */
    public readonly string $teamId;

    /**
     * @var string $apiAppId Example: A123ABC456
     */
    public readonly string $apiAppId;

    public readonly AppMentionEventDto $event;

    /**
     * @var string $type Example: A123ABC456
     */
    public readonly string $type;

    /**
     * @var string $eventId Example: Ev123ABC456
     */
    public readonly string $eventId;

    /**
     * @var string $eventTime Example: 1515449522000016
     */
    public readonly string $eventTime;

    /**
     * @var array<string> $authedUsers Example: ["U0LAN0Z89"]
     */
    public readonly array $authedUsers;

    public function __construct(array $data)
    {
        $this->token = $data['token'];
        $this->teamId = $data['team_id'];
        $this->apiAppId = $data['api_app_id'];
        $this->event = new AppMentionEventDto($data['event']);
        $this->type = $data['type'];
        $this->eventId = $data['event_id'];
        $this->eventTime = $data['event_time'];
        $this->authedUsers = $data['authed_users'];
    }

}