<?php namespace Modules\Absplit\Http\Controllers;

use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class AbsplitController extends Controller
{
    public function index()
    {
	return View::make('absplit::index');
    }
    public function getDashboard()
    {
	return View::make('absplit::dashboard');
    }
}