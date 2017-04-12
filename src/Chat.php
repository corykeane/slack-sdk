<?php

namespace CoryKeane\Slack;

class Chat
{
    protected $client;
    protected $channel;

    public function __construct(Client $client, $channel)
    {
        $this->client = $client;
        $this->channel = $channel;
    }

    public function send($message = null, $attachments = null)
    {
        $config = $this->client->getConfig();
        $query = array_merge([
            'text' => $message, 'channel' => $this->channel, 'attachments' => json_encode($attachments)
        ], $config);

        $request = $this->client->request('chat.postMessage', $query)->send();
        $response = new Response($request);
        if ($this->client->debug) {
            if (!$response->isOkay()) {
                echo '[Error] '.$response->getError().'.'.PHP_EOL;
                echo '[Query] '.var_export($this->client->request('chat.postMessage', $query)->getQuery(), true);
                return false;
            }

            return true;
        }
    }
}
