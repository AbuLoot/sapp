<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Unit::class);

        $units = Unit::paginate(50);

        return view('pos.units.index', compact('units'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Unit::class);

        return view('pos.units.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Unit::class);

        $this->validate($request, [
            'title' => 'required|max:80|unique:units',
        ]);

        $unit = new Unit;

        $unit->title = $request->title;
        $unit->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $unit->description = $request->description;
        $unit->lang = $request->lang;
        $unit->save();

        return redirect($request->lang.'/pos/units')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $unit = Unit::findOrFail($id);

        // $this->authorize('update', $unit);

        return view('pos.units.edit', compact('unit'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:80',
        ]);

        $unit = Unit::findOrFail($id);

        // $this->authorize('update', $unit);

        $unit->title = $request->title;
        $unit->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $unit->description = $request->description;
        $unit->lang = $request->lang;
        $unit->save();

        return redirect($lang.'/pos/units')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $unit = Unit::find($id);

        // $this->authorize('delete', $unit);

        $unit->delete();

        return redirect($lang.'/pos/units')->with('status', 'Запись удалена.');
    }
}