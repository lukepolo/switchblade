<?php

namespace App\Models\Traits;

use App\Models\Scopes\ByUserScope;

trait ByUser
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootByUser()
    {
        static::addGlobalScope(new ByUserScope);
    }

}