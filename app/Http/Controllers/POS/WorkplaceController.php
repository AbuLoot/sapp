<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Store;
use App\Models\Cashbook;
use App\Models\Workplace;

class WorkplaceController extends Controller
{
    public $companyId;

    public function index()
    {
        // $this->authorize('viewAny', Workplace::class);

        $workplaces = Workplace::where('company_id', $this->companyId)->paginate(50);

        return view('pos.workplaces.index', compact('workplaces'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Workplace::class);

        $users = User::where('company_id', $this->companyId)->where('is_worker', 1)->get();
        $stores = Store::where('company_id', $this->companyId)->get();
        $cashbooks = Cashbook::where('company_id', $this->companyId)->get();

        return view('pos.workplaces.create', compact('users', 'stores', 'cashbooks'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Workplace::class);

        $this->validate($request, [
            'user_id' => 'required|numeric',
            'workplace_id' => 'required|min:6',
            'code' => 'required|numeric|min:4'
        ]);

        list($workplace_type, $workplace_id) = explode('-', $request->workplace_id);

        $workplace = new Workplace;
        $workplace->company_id = $this->companyId;
        $workplace->user_id = $request->user_id;
        $workplace->workplace_id = $workplace_id;
        $workplace->workplace_type = 'App\\Models\\'.Str::ucfirst($workplace_type);
        $workplace->code = $request->code;
        $workplace->comment = $request->comment;
        $workplace->save();

        return redirect($request->lang.'/pos/workplaces')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $workplace = Workplace::where('company_id', $this->companyId)->findOrFail($id);
        // $this->authorize('update', $workplace);
        $users = User::where('company_id', $this->companyId)->where('is_worker', 1)->get();
        $stores = Store::where('company_id', $this->companyId)->get();
        $cashbooks = Cashbook::where('company_id', $this->companyId)->get();

        return view('pos.workplaces.edit', compact('workplace', 'users', 'stores', 'cashbooks'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'workplace_id' => 'required|min:6',
            'code' => 'required|numeric|min:4'
        ]);

        $workplace = Workplace::where('company_id', $this->companyId)->findOrFail($id);

        // $this->authorize('update', $workplace);

        list($workplace_type, $workplace_id) = explode('-', $request->workplace_id);

        $workplace->user_id = $request->user_id;
        $workplace->workplace_id = $workplace_id;
        $workplace->workplace_type = 'App\\Models\\'.Str::ucfirst($workplace_type);
        $workplace->code = $request->code;
        $workplace->comment = $request->comment;
        $workplace->save();

        return redirect($lang.'/pos/workplaces')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $workplace = Workplace::where('company_id', $this->companyId)->find($id);

        // $this->authorize('delete', $workplace);

        $workplace->delete();

        return redirect($lang.'/pos/workplaces')->with('status', 'Запись удалена.');
    }
}