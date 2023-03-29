<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class SelectCompanyController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.select-company', ['companies' => Company::where('sn_client', true)->get()]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => ['required', 'integer', 'max:255'],
        ]);

        $company = Company::findOrFail($request->company_id);

        session()->put('selectedCompany', $company);

        return redirect(app()->getLocale().'/pos');
    }
}
