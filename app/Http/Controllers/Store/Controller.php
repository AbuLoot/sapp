<?php

namespace App\Http\Controllers\Store;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Language;
use App\Models\Project;
use App\Models\Company;
use App\Models\Category;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        app()->setLocale(\Request::segment(1));

        // $companies = Company::where('status', 1)->orderBy('sort_id')->get();
        // $projects = Project::orderBy('sort_id')->where('status', '<>', 0)->get()->toTree();
        // $categories = Category::orderBy('sort_id')->where('status', '<>', 0)->get()->toTree();

        view()->share([
            'lang' => app()->getLocale(),
            // 'companies' => $companies,
            // 'projects' => $projects,
            // 'categories' => $categories,
        ]);
    }
}
