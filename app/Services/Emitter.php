<?php

namespace App\Services;


use \ElephantIO\Client;
use \ElephantIO\Engine\SocketIO\Version1X;

class Emitter
{
    public $client;
    
    public function __construct()
    {
        $this->client = new Client(new Version1X('https://localhost:7777'));
        $this->client->initialize();
    }
    
    public function append($namespace, $element, $data, $callback, $users = array())
    {
        echo 'test'; die;
        $this->client->emit('append', ['foo' => 'bar']);
        $this->client->close();
    }
    
    public function apply_broadcast($function, $location, $data)
    {
        $this->client->emit('apply_broadcast', [
            'function' => $function,
            'location' => $location,
            'data' => $data
        ]);
        $this->client->close();
    }
    
    public function __destruct() 
    {
       echo 'yay'; die;
    }
}
