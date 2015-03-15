<?php

namespace Modules\Screenshot\Models\Mongo;

class Screenshot extends \Moloquent
{
    use \App\Models\Traits\ByUser;

    protected $connection = 'mongodb';
    // Everything is guarded
}