<?php namespace CoryKeane\Slack;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    protected $rawResponse;
    protected $response;
    protected $errorTypes = array(
        'invalid_auth'      => "Invalid authentication token",
        'account_inactive'  => "Authentication token is for a deleted user or team",
        'channel_not_found' => "Value passed for channel was invalid",
        'is_archived'       => "Channel has been archived",
        'not_authed'        => "No authentication token provided."
    );
    protected $error = null;

    public function __construct(GuzzleResponse $response)
    {
        $this->rawResponse = $response;
        $this->response = json_decode((string) $this->rawResponse->getBody());
    }

    public function statusCode()
    {
        return $this->rawResponse->getStatusCode();
    }

    public function isOkay()
    {
        if(!$this->response->ok)
        {
            $this->error = $this->errorTypes[$this->response->error] ?? "Unidentified error (".$this->response->error.")";
        
            pre($this->error); die();
        }

        return $this->response->ok;
    }

    public function getError()
    {
        return $this->error;
    }
}
