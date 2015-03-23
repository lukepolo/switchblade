<?php

namespace App\Http\Controllers;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use App\Models\User;

abstract class RestController extends BaseController
{
    use DispatchesCommands, ValidatesRequests;
    public function __construct(Request $request)
    {
	\Log::info($_SERVER);

	// Stops From Invalid Requests also stops Phantom JS from executing other commands
	if(\Request::has('api_key') && $_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR'])
	{
	    $user = User::where('api_key', '=', \Request::input('api_key'))->first();
	    if(empty($user))
	    {
		\App::abort(401, 'Unauthorized User.');
	    }
	    else
	    {
		// Make it globabl so we can use it later
		\App::instance('user', $user);
	    }
	}
	else
	{
	    \App::abort(401, 'Unauthorized User.');
	}
    }
}
