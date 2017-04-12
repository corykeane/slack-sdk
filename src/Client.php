<?php namespace CoryKeane\Slack;

use GuzzleHttp\Client as GuzzleClient;

use CoryKeane\Slack\Webhooks\Incoming as IncomingWebhook;

class Client
{
    const CLIENT_NAME = 'Slack-SDK';
    const CLIENT_VERSION = '1.1.0';
    const CLIENT_URL = 'https://github.com/corykeane/slack-sdk';
    const API_URL = 'https://slack.com/api';
    const DEFAULT_CHANNEL = '#random';
    public $config = [];

    /**
     * @var GuzzleClient
     */
    public $client;
    public $debug = true;

    public function __construct(array $config = [])
    {
        $this->config = array(
            'token' => $config['token'],
            'username' => $config['username'],
            'icon_url' => (strpos($config['icon'], 'http') !== false) ? $config['icon'] : null,
            'icon_emoji' => (strpos($config['icon'], 'http') !== false) ? null : $config['icon'],
            'parse' => $config['parse'],
        );
        $this->client = new GuzzleClient(['base_uri' => self::API_URL, 'timeout' => 2.0]);
    }

    public function setUserAgent()
    {
        return self::CLIENT_NAME.'/'.self::CLIENT_VERSION.' (+'.self::CLIENT_URL.')';
    }

    public function setDebug($debug = false)
    {
        $this->debug = $debug;
        return $this;
    }

    public function setConfig($config = [])
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig($keys = null)
    {
        if (!is_null($keys) && is_array($keys)) {
            $config = [];
            foreach ($this->config as $key => $value) {
                if (in_array($key, $keys)) {
                    $config[$key] = $value;
                }
            }
            return $config;
        }
        return $this->config;
    }

    public function request($endpoint = null, array $query = [])
    {
        return $this->client->request('GET', $endpoint, ['query' => $query, 'debug' => true,]);
    }

    public function listen($simulate = false)
    {
        if (empty($_POST) && !$simulate) {
            return false;
        }
        $hook = new IncomingWebhook($this);
        if (is_array($simulate)) {
            return $hook->simulatePayload($simulate);
        }
        return $hook;
    }

    public function chat($channel = self::DEFAULT_CHANNEL)
    {
        return new Chat($this, $channel);
    }

    public function users()
    {
        $query = $this->getConfig(['token']);
        $response = $this->request('users.list', $query)->send()->json();
        $users = [];
        foreach ($response['members'] as $member) {
            $users[] = new User($member);
        }
        return $users;
    }
}
