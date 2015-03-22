<?php

namespace Modules\Tracer\Models\Mongo;

class Bug extends \Moloquent
{
    use \App\Models\Traits\ByUser;
    
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
    
    public function history()
    {
        return $this->hasOne('Modules\Tracer\Models\Mongo\BugHistory');
    }
    
    public function browser()
    {
        return $this->hasMany('Modules\Tracer\Models\Mongo\BugBrowser');
    }
    
    public function users()
    {
        return $this->hasMany('Modules\Tracer\Models\Mongo\BugUser');
    }
}