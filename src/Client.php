<?php namespace ThreadMeUp\Slack;

use Guzzle\Http\Client as GuzzleClient;

use ThreadMeUp\Slack\Webhooks\Incoming as IncomingWebhook;

class Client {

    const CLIENT_NAME = 'Slack-SDK';
    const CLIENT_VERSION = '1.1.0';
    const CLIENT_URL = 'https://github.com/threadmeup/slack-sdk';
    const API_URL = 'https://slack.com/api';
    const DEFAULT_CHANNEL = '#random';
    public $config = array();
    public $client;
    public $debug = true;

    public function __construct(array $config = array())
    {
        $this->config = array(
            'token' => $config['token'],
            'username' => $config['username'],
            'icon_url' => ((strpos($config['icon'], 'http') !== false) ? $config['icon'] : null),
            'icon_emoji' => ((strpos($config['icon'], 'http') !== false) ? null : $config['icon']),
            'parse' => $config['parse']
        );
        $this->client = new GuzzleClient(self::API_URL);
        $this->client->setUserAgent($this->setUserAgent());
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

    public function setConfig($config = array())
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig($keys = null)
    {
        if (!is_null($keys) && is_array($keys))
        {
            $config = array();
            foreach ($this->config as $key => $value)
            {
                if (in_array($key, $keys))
                {
                    $config[$key] = $value;
                }
            }
            return $config;
        }
        return $this->config;
    }

    public function request($endpoint = null, array $query = array())
    {
        return $this->client->get($endpoint, array(), array('query' => $query), array('debug' => $this->debug));
    }

    public function listen($simulate = false)
    {
        if (empty($_POST) && !$simulate) return false;
        $hook = new IncomingWebhook($this);
        if (is_array($simulate)) return $hook->simulatePayload($simulate);
        return $hook;
    }

    public function chat($channel = self::DEFAULT_CHANNEL)
    {
        return new Chat($this, $channel);
    }

    public function users()
    {
        $query = $this->getConfig(array('token'));
        $response = $this->request('users.list', $query)->send()->json();
        $users = array();
        foreach ($response['members'] as $member)
        {
            $users[] = new User($member);
        }
        return $users;
    }
}
