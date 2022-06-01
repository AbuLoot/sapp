<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Region;
use App\Models\Company;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Store::class);

        $stores = Store::paginate(50);

        return view('joystick.stores.index', compact('stores'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Store::class);

        $regions = Region::orderBy('sort_id')->get()->toTree();

        return view('joystick.stores.create', compact('regions'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Store::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:stores',
            'ip_address' => 'required|min:6',
        ]);

        $company = auth()->user()->profile->company->first();

        $store = new Store;
        $store->company_id = $company->id;
        $store->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $store->title = $request->title;
        $store->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $store->ip_address = $request->ip_address;
        $store->address = $request->address;
        $store->description = $request->description;
        // $store->status = ($request->status == 'on') ? 1 : 0;
        $store->save();

        return redirect($request->lang.'/admin/stores')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $store = Store::findOrFail($id);

        // $this->authorize('update', $store);

        return view('joystick.stores.edit', compact('regions', 'store'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $store = Store::findOrFail($id);

        // $this->authorize('update', $store);

        // $store->company_id = $company->id;
        $store->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $store->title = $request->title;
        $store->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $store->ip_address = $request->ip_address;
        $store->address = $request->address;
        $store->description = $request->description;
        // $store->status = ($request->status == 'on') ? 1 : 0;
        $store->save();

        return redirect($lang.'/admin/stores')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $store = Store::find($id);

        // $this->authorize('delete', $store);

        $store->delete();

        return redirect($lang.'/admin/stores')->with('status', 'Запись удалена.');
    }
}