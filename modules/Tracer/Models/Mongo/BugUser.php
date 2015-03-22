<?php

namespace Modules\Tracer\Models\Mongo;

class BugUser extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}