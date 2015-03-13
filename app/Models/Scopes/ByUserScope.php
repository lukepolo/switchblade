<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ByUserScope implements ScopeInterface
{
    /**
    * Apply the scope to a given Eloquent query builder.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $builder
    * @param  \Illuminate\Database\Eloquent\Model  $model
    * @return void
    */
   public function apply(Builder $builder, Model $model)
   {

	if(class_exists('app') === true)
	{
	    $user = \App::make('user');
	    $builder->where('user_id', '=', $user->id);
	}
	else
	{
	    $builder->where('user_id', '=', \Auth::user()->id);
	}
   }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
	echo 'TODO - DO I NEED THIS?'; die;
    }
}
