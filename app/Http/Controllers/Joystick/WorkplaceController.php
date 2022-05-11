<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Storage;
use App\Models\Cashbook;
use App\Models\Workplace;

class WorkplaceController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Workplace::class);

        $workplaces = Workplace::paginate(50);

        return view('joystick.workplaces.index', compact('workplaces'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Workplace::class);
        $users = User::where('is_worker', 1)->get();
        $storages = Storage::get();
        $cashbooks = Cashbook::get();

        return view('joystick.workplaces.create', compact('users', 'storages', 'cashbooks'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Workplace::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:workplaces',
        ]);

        $company = auth()->user()->profile->company->first();

        $workplace = new Workplace;
        $workplace->company_id = $company->id;
        $workplace->title = $request->title;
        $workplace->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $workplace->account_number = $request->account_number;
        $workplace->bic = $request->bic;
        $workplace->balance = $request->balance;
        $workplace->currency = $request->currency;
        $workplace->comment = $request->comment;
        $workplace->save();

        return redirect($request->lang.'/admin/workplaces')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $workplace = Workplace::findOrFail($id);
        $currencies = Currency::orderBy('sort_id')->get();

        // $this->authorize('update', $workplace);

        return view('joystick.workplaces.edit', compact('currencies', 'workplace'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $workplace = Workplace::findOrFail($id);

        // $this->authorize('update', $workplace);

        // $workplace->company_id = $company->id;
        $workplace->title = $request->title;
        $workplace->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $workplace->account_number = $request->account_number;
        $workplace->bic = $request->bic;
        $workplace->balance = $request->balance;
        $workplace->currency = $request->currency;
        $workplace->comment = $request->comment;
        // $workplace->status = ($request->status == 'on') ? 1 : 0;
        $workplace->save();

        return redirect($lang.'/admin/workplaces')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $workplace = Workplace::find($id);

        // $this->authorize('delete', $workplace);

        $workplace->delete();

        return redirect($lang.'/admin/workplaces')->with('status', 'Запись удалена.');
    }
}