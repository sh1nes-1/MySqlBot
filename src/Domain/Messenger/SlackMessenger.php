<?php

namespace Sh1ne\MySqlBot\Domain\Messenger;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class SlackMessenger implements Messenger
{

    private Client $client;

    private string $channel;

    private string $threadTs;

    public function __construct(Client $client, string $channel, string $threadTs)
    {
        $this->client = $client;
        $this->channel = $channel;
        $this->threadTs = $threadTs;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function sendMessage(string $message) : void
    {
        $response = $this->client->post('chat.postMessage', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            RequestOptions::JSON => [
                'channel' => $this->channel,
                'thread_ts' => $this->threadTs,
                'text' => $message,
            ],
        ]);

        $body = $response->getBody()->getContents();

        $response = json_decode($body, true);

        if ($response['ok'] !== true) {
            throw new Exception($body);
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function uploadFile(File $file, string $message) : void
    {
        $response = $this->client->post('files.upload', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            ],
            RequestOptions::FORM_PARAMS => [
                'channels' => $this->channel,
                'thread_ts' => $this->threadTs,
                'initial_comment' => $message,
                'filename' => $file->getName(),
                'filetype' => $file->getType(),
                'content' => $file->getContent(),
            ],
        ]);

        $body = $response->getBody()->getContents();

        $response = json_decode($body, true);

        if ($response['ok'] !== true) {
            throw new Exception($body);
        }
    }

}