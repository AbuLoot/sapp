<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Region;
use App\Models\Company;
use App\Models\Storage;

use Auth;

class StorageController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Storage::class);

        $storages = Storage::paginate(50);

        return view('joystick.storages.index', compact('storages'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Storage::class);

        $regions = Region::orderBy('sort_id')->get()->toTree();

        return view('joystick.storages.create', compact('regions'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Storage::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:storages',
        ]);

        $company = Auth::user()->profile->company->first();

        $storage = new Storage;
        $storage->company_id = $company->id;
        $storage->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $storage->title = $request->title;
        $storage->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $storage->ip_address = $request->ip_address;
        $storage->address = $request->address;
        $storage->description = $request->description;
        // $storage->status = ($request->status == 'on') ? 1 : 0;
        $storage->save();

        return redirect($request->lang.'/admin/storages')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $storage = Storage::findOrFail($id);

        // $this->authorize('update', $storage);

        return view('joystick.storages.edit', compact('regions', 'storage'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $storage = Storage::findOrFail($id);

        // $this->authorize('update', $storage);

        // $storage->company_id = $company->id;
        $storage->region_id = ($request->region_id > 0) ? $request->region_id : 0;
        $storage->title = $request->title;
        $storage->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $storage->ip_address = $request->ip_address;
        $storage->address = $request->address;
        $storage->description = $request->description;
        // $storage->status = ($request->status == 'on') ? 1 : 0;
        $storage->save();

        return redirect($lang.'/admin/storages')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $storage = Storage::find($id);

        // $this->authorize('delete', $storage);

        $storage->delete();

        return redirect($lang.'/admin/storages')->with('status', 'Запись удалена.');
    }
}