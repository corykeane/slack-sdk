<?php namespace ThreadMeUp\Slack;

class Chat {

    protected $client;
    protected $channel;

    public function __construct(Client $client, $channel)
    {
        $this->client = $client;
        $this->channel = $channel;
    }

    public function send($message = null)
    {
        $config = $this->client->getConfig();
        $query = array_merge(array('text' => $message, 'channel' => $this->channel), $config);
        $request = $this->client->request('chat.postMessage', $query)->send();
        $response = new Response($request);
        if ($this->client->debug)
        {
            if ($response->isOkay())
            {
                echo $this->client->config['username'].' ['.$this->channel.']: '.$message.PHP_EOL;
                return true;
            }
            else
            {
                echo '[Error] '.$response->getError().'.'.PHP_EOL;
                return false;
            }
        }
    }
}
