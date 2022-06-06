<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Page;
use App\Models\Section;
use App\Models\Language;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $pages = Page::where('status', 1)->whereNotIn('slug', ['/'])->orderBy('sort_id')->get()->toTree();
        $sections = Section::whereIn('slug', ['header-code', 'footer-code', 'contacts', 'soc-networks'])->get();

        view()->share([
            'lang' => app()->getLocale(),
            'pages' => $pages,
            'sections' => $sections,
        ]);
    }
}
