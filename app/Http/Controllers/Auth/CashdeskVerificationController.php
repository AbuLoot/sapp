<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Workplace;
use App\Models\Cashbook;

class CashdeskVerificationController extends Controller
{
    public function create()
    {
        return view('auth.verification');
    }

    public function store(Request $request)
    {
        $workplaces = Workplace::where('user_id', auth()->user()->id)
                ->where('workplace_type', 'App\Models\Cashbook')->get();

        $request->validate([
            'code' => [
                'required', 'integer', 'min:4', Rule::in($workplaces->pluck('code')->toArray())
            ],
        ]);

        $workplace = $workplaces->where('code', $request->code)->first();
        $cashbook = Cashbook::find($workplace->workplace_id);

        $request->session()->put('cashbook', $cashbook);
        $request->session()->put('cashdeskWorkplace', $workplace->id);

        return redirect(app()->getLocale().'/cashdesk');
    }
}
