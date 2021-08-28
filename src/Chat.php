<?php namespace CoryKeane\Slack;

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

        pre($response->isOkay()); die();

        if ($this->client->debug)
        {
            if ($response->isOkay())
            {
                //echo $this->client->config['username'].' ['.$this->channel.']: '.$message.PHP_EOL;
                return true;
            }
            else
            {
                echo '[Error] '.$response->getError().'.'.PHP_EOL;
                echo '[Query] '.var_export($this->client->request('chat.postMessage', $query)->getQuery(), true);
                return false;
            }
        }
    }
}
