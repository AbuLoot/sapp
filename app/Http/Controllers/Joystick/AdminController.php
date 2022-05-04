<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Joystick\Controller;

class AdminController extends Controller
{
    public function index()
    {
    	return view('joystick.index');
    }

    public function filemanager()
    {
        if (! Gate::allows('allow-filemanager', \Auth::user())) {
            abort(403);
        }

    	return view('joystick.filemanager');
    }

    public function frameFilemanager()
    {
    	return view('joystick.frame-filemanager');
    }
}
