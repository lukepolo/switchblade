<?php

namespace Modules\Tracer\Models\Mongo;

class BugHistory extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}