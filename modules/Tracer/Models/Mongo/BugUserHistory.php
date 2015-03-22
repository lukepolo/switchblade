<?php

namespace Modules\Tracer\Models\Mongo;

class BugUserHistory extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}