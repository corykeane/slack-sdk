<?php namespace CoryKeane\Slack;

use \Exception; 

class Chat {

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
        $query = array('text' => $message, 'channel' => $this->channel, 'attachments' => (!empty($attachments)) ? json_encode($attachments) : null) + $config;
    
        $request = $this->client->request('chat.postMessage', $query); 

        $response = new Response($request);

        if(!$this->client->debug || $response->isOkay())
        {
            return $response->isOkay();
        }

        throw new Exception($response->getError() . " (" . var_export($this->client->request('chat.postMessage', $query)->getQuery(), true) . ")");
    }
}
