<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Region;
use App\Models\Company;
use App\Models\Cashbook;

class CashbookController extends Controller
{
    public $companyId;

    public function index()
    {
        // $this->authorize('viewAny', Cashbook::class);

        $cashbooks = Cashbook::where('company_id', $this->companyId)->paginate(50);

        return view('pos.cashbooks.index', compact('cashbooks'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Cashbook::class);

        $regions = Region::orderBy('sort_id')->get()->toTree();

        return view('pos.cashbooks.create', compact('regions'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Cashbook::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $numId = $request->num_id ?? Cashbook::where('company_id', $this->companyId)->count() + 1;

        $cashbook = new Cashbook;
        $cashbook->num_id = $numId;
        $cashbook->company_id = $this->companyId;
        $cashbook->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $cashbook->title = $request->title;
        $cashbook->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $cashbook->ip_address = $request->ip_address;
        $cashbook->address = $request->address;
        $cashbook->description = $request->description;
        // $cashbook->status = ($request->status == 'on') ? 1 : 0;
        $cashbook->save();

        return redirect($request->lang.'/pos/cashbooks')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $cashbook = Cashbook::where('company_id', $this->companyId)->findOrFail($id);

        // $this->authorize('update', $cashbook);

        return view('pos.cashbooks.edit', compact('regions', 'cashbook'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $cashbook = Cashbook::where('company_id', $this->companyId)->findOrFail($id);

        $numId = ($request->num_id) ? $request->num_id : Cashbook::where('company_id', $this->companyId)->count() + 1;

        // $this->authorize('update', $cashbook);

        // $cashbook->num_id = $numId;
        // $cashbook->company_id = $this->companyId;
        $cashbook->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $cashbook->title = $request->title;
        $cashbook->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $cashbook->ip_address = $request->ip_address;
        $cashbook->address = $request->address;
        $cashbook->description = $request->description;
        // $cashbook->status = ($request->status == 'on') ? 1 : 0;
        $cashbook->save();

        return redirect($lang.'/pos/cashbooks')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $cashbook = Cashbook::where('company_id', $this->companyId)->find($id);

        // $this->authorize('delete', $cashbook);

        $cashbook->delete();

        return redirect($lang.'/pos/cashbooks')->with('status', 'Запись удалена.');
    }
}