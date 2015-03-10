<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function getIndex()
    {

	return view('settings', ['settings' => \App\Models\Settings::get()]);
    }
}

