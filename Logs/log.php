<?php

class log
{
    private $filled = false;

    private $server;
    private $base;
    private $event;
    private $status;
    private $action;

    private $username;
    private $password;
    private $full_access;
    private $email;

    function __construct($console_args){
        if (count($console_args)===5) $this->setLog($console_args);
        else if (count($console_args)===2) $this->setUser($console_args);
    }
    
    public function setLog($options)
    {
        if(isset($options["server"]) and isset($options["base"])
            and isset($options["event"]) and isset($options["status"]) and isset($options["action"])) {
            $this->server = $options["server"];
            $this->base = $options["base"];
            $this->event = $options["event"];
            $this->status = (int)$options["status"];
            $this->action = $options["action"];
            $this->filled=true;
        }
    }

    public function setUser($options)
    {
        if(isset($options["username"]) and isset($options["password"])) {
            $this->username = $options["username"];
            $this->password = $options["password"];
            $this->full_access = $options["full_access"];
            $this->email = $options["email"];
            $this->filled=true;
        }
    }

    public function getServer(): string { return $this->server; }
    public function getBase(): string { return $this->base; }
    public function getEvent(): string { return $this->event; }
    public function getStatus(): int{ return $this->status; }
    public function getFilled(): bool{ return $this->filled; }
    public function getAction() : string{ return $this->action; }
    public function getUsername() : string{ return $this->username; }
    public function getPassword() : string{ return $this->password; }
    public function getFullAccess() : bool{ return $this->full_access; }
    public function getEmail() : bool{ return $this->email; }
}