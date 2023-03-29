<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Language;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $companyId;

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            $this->companyId = session('company')->id;
            return $next($request);
        });

        app()->setLocale(\Request::segment(1));

        $languages = Language::orderBy('sort_id')->get();

        view()->share([
            'lang' => app()->getLocale(),
            'languages' => $languages
        ]);
    }
}
