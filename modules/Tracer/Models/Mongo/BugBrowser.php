<?php

namespace Modules\Tracer\Models\Mongo;

class BugBrowser extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}