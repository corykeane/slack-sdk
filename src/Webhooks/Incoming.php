<?php namespace CoryKeane\Slack\Webhooks;

use CoryKeane\Slack\Client;

class Incoming
{
    protected $payload;
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->payload = $_POST;
    }

    public function simulatePayload(array $payload = array())
    {
        $this->payload = $payload;
        return $this;
    }

    public function getPayload($key = null)
    {
        return is_null($key) ? $this->payload : @$this->payload[$key];
    }

    public function token()
    {
        return $this->getPayload('token');
    }

    public function teamId()
    {
        return $this->getPayload('team_id');
    }

    public function channelId()
    {
        return $this->getPayload('channel_id');
    }

    public function channel()
    {
        return $this->getPayload('channel_id');
    }

    public function timestamp()
    {
        return $this->getPayload('timestamp');
    }

    public function userId()
    {
        return $this->getPayload('user_id');
    }

    public function user()
    {
        return $this->getPayload('user_name');
    }

    public function rawText()
    {
        return $this->getPayload('text');
    }

    public function words()
    {
        return explode(' ', $this->rawText());
    }

    public function trigger()
    {
        if ($this->getPayload('trigger_word')) {
            return $this->getPayload('trigger_word');
        } elseif ($this->getPayload('command')) {
            return $this->getPayload('command');
        }
        return "";
    }

    public function text()
    {
        $words = $this->words();
        array_shift($words);
        return implode(' ', $words);
    }

    public function respond($response, $channel = null)
    {
        $channel = is_null($channel) ? $this->channel() : $channel;
        return $this->client->chat($channel)->send($response);
    }
}
