<?php namespace Killswitch\Slack;

use Guzzle\Http\Client as GuzzleClient;

class Client
{
    const CLIENT_NAME = 'Slack-SDK';
    const CLIENT_VERSION = '1.0';
    const CLIENT_URL = 'https://github.com/killswitch/slack-sdk';
    private $client;
    private $subdomain;
    private $token;
    private $debug = false;
    private $config = array();

    public function __construct($subdomain, $token)
    {
        $this->subdomain = $subdomain;
        $this->token = $token;
        $this->client = new GuzzleClient('https://'.$subdomain.'.slack.com');
        $this->client->setDefaultOption('query', array('token' => $token));
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

    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setConfig($config = array())
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function say($channel, $message)
    {
        $payload = $this->buildPayload(array('text' => $message, 'channel' => $channel));
        $request = $this->request($payload)->send();
        if ($this->debug) echo $this->config['username'].' ['.$channel.']: '.$message.PHP_EOL;
    }

    private function buildPayload($data)
    {
        return json_encode(array_merge($data, $this->config));
    }

    private function request($payload)
    {
        return $this->client->post('/services/hooks/incoming-webhook', array(), $payload, array('debug' => $this->debug));
    }
}
