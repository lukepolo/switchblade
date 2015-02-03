<?php

// Just needed the allow origin from another server
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

class Security extends Fuel\Core\Security
{
    
}