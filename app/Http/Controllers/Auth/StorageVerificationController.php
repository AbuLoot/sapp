<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Workplace;
use App\Models\Store;

class StorageVerificationController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.verification');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $workplaces = Workplace::where('user_id', auth()->user()->id)
                ->where('workplace_type', 'App\Models\Store')->get();

        $request->validate([
            'code' => [
                'required', 'integer', 'min:4', Rule::in($workplaces->pluck('code')->toArray())
            ],
        ]);

        $workplace = $workplaces->where('code', $request->code)->first();
        // $store = Store::find($workplace->workplace_id);

        $request->session()->put('storageWorkplace', $workplace->id);

        return redirect('/'.app()->getLocale().'/storage');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->back();
    }
}
