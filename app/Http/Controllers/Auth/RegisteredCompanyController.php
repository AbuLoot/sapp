<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Region;
use App\Models\Currency;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RegisteredCompanyController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register-company', ['regions' => Region::get()->toTree(), 'currencies' => Currency::get()]);
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
        $userGuard = User::where('id', 1)->orWhere('email', 'admin@sanapp.kz')->first();
        $values = explode('/', $userGuard->profile->about);

        $request->validate([
            'title' => ['required', 'min:2', 'max:80'],
            'bin' => ['required', 'string', 'max:80'],
            'region_id' => ['required', 'integer', 'max:25'],
            'currency_id' => ['required', 'integer', 'max:25'],
            'phones' => ['required', 'string', 'min:7', 'max:25'],
            'email' => ['required', 'string', 'min:7', 'max:25'],
            // 'links' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:1000'],
            'legal_address' => ['required', 'string', 'max:255'],
            'code' => ['required', Rule::in($values)],
            // 'actual_address' => ['required', 'string', 'max:255'],
        ]);

        $company = Company::create([
            'sort_id' => Company::count() + 1,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'bin' => $request->bin,
            'image' => 'no-image-mini.png',
            'region_id' => $request->region_id,
            'currency_id' => $request->currency_id,
            'phones' => $request->tel,
            'emails' => $request->email,
            'links' => $request->links,
            'about' => $request->about,
            'legal_address' => $request->legal_address,
            'actual_address' => $request->actual_address,
            'sn_client' => 1,
        ]);

        $user = auth()->user();
        $user->company_id = $company->id;
        $user->is_worker = 1;
        $user->save();

        $role = Role::where('name', 'manager')->first();

        $user->roles()->attach($role->id);

        $request->session()->put('company', $company);

        return redirect(app()->getLocale().'/apps');
    }
}
