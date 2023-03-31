<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyVerificationController extends Controller
{
    public function create()
    {
        return view('auth.register-company');
    }

    public function store(Request $request)
    {
        $company = Company::where('user_id', auth()->user()->id)
                ->where('is_member', true)
                ->first();

        $cashbook = Cashbook::where('company_id', session('company')->id)->find($workplace->workplace_id);

        $request->session()->put('company', $company->id);

        return redirect(app()->getLocale().'/apps');
    }
}
