<?php

namespace App;

class Ip {

    private $provider = "http://ifconfig.me/ip";

    public function __construct() {
        $this->ip = file_get_contents($this->provider);
        
        if (!filter_var($this->ip, FILTER_VALIDATE_IP)) {
            throw new \Exception('ip wan invalide');
        }
    }

    public function __toString() {
        return $this->ip;
    }
}