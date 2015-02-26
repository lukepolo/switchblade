<?php namespace Modules\Absplit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class AbsplitController extends Controller {

	public function index()
	{
		return View::make('absplit::index');
	}
	
}