<?php namespace ThreadMeUp\Slack;

class User {

    protected $data;

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function id()
    {
        return $this->data['id'];
    }

    public function name()
    {
        return (empty($this->data['real_name']) ? $this->data['name'] : $this->data['real_name']);
    }

    public function handle()
    {
        return $this->data['name'];
    }

    public function isDeleted()
    {
        return $this->data['deleted'];
    }

    public function status()
    {
        return $this->data['status'];
    }

    public function color()
    {
        return '#'.$this->data['color'];
    }

    public function skype()
    {
        return $this->data['skype'];
    }

    public function phone()
    {
        return $this->data['phone'];
    }

    public function timezone()
    {
        return $this->data['tz'];
    }

    public function timezoneOffset()
    {
        return $this->data['tz_offset'];
    }

    public function email()
    {
        return $this->data['profile']['email'];
    }

    public function picture()
    {
        return $this->data['profile']['image_192'];
    }

    public function isAdmin()
    {
        return $this->data['is_admin'];
    }

    public function isOwner()
    {
        return $this->data['is_owner'];
    }

    public function isPrimaryOwner()
    {
        return $this->data['is_primary_owner'];
    }

    public function hasFiles()
    {
        return $this->data['has_files'];
    }
}
