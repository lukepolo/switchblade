<?php

class Controller_Api extends \Controller_Rest
{
    public function get_mods()
    {
        // Based on their activated mods , grab the JS code to execute
        $mods[] = \ABSplit\Controller_Api::get_code();
        $mods[] = \HeatMap\Controller_Api::get_code();
        
        return $this->response($mods);
    }
}