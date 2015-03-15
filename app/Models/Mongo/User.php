<?php

namespace App\Models\Mongo;

class User extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}
