<?php

class Controller_Api extends \Controller_Rest
{
    public function get_mods()
    {
        // Based on their activated mods , grab the JS code to execute
        $absplit = \ABSplit\Controller_Api::get_code();
        echo $absplit;
    }
}