<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
use Illuminate\Http\Request;

use App\Models\CashDoc;
use App\Models\Product;
use App\Models\OutgoingDoc;

class CashDocController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', CashDoc::class);

        $cashdocs = CashDoc::orderByDesc('id')->paginate(50);

        return view('joystick.cashdocs.index', compact('cashdocs'));
    }

    public function show($lang, $id)
    {
        $cashdoc = CashDoc::findOrFail($id);
        $outgoingDoc = OutgoingDoc::findOrFail($cashdoc->doc_id);
        $productsData = json_decode($outgoingDoc->products_data, true);
        $productsKeys = collect($productsData)->keys();
        $docProducts = Product::whereIn('id', $productsKeys->all())->get();

        return view('joystick.cashdocs.show', compact('cashdoc', 'docProducts'));
    }

    public function create($lang)
    {
        // $this->authorize('create', CashDoc::class);

        return view('joystick.cashdocs.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', CashDoc::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:255|unique:cashdocs',
        ]);

        $cashdoc = new CashDoc;

        $cashdoc->type = $request->type;
        $cashdoc->title = $request->title;
        $cashdoc->slug = (empty($request->slug)) ? Str::slug($request->type) : $request->slug;
        $cashdoc->description = $request->description;
        $cashdoc->lang = $request->lang;
        $cashdoc->save();

        return redirect($request->lang.'/admin/cashdocs')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $cashdoc = CashDoc::findOrFail($id);

        // $this->authorize('update', $cashdoc);

        return view('joystick.cashdocs.edit', compact('cashdoc'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:255',
        ]);

        $cashdoc = CashDoc::findOrFail($id);

        // $this->authorize('update', $cashdoc);

        $cashdoc->type = $request->type;
        $cashdoc->title = $request->title;
        $cashdoc->slug = (empty($request->slug)) ? Str::slug($request->type) : $request->slug;
        $cashdoc->description = $request->description;
        $cashdoc->lang = $request->lang;
        $cashdoc->save();

        return redirect($lang.'/admin/cashdocs')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $cashdoc = CashDoc::find($id);

        // $this->authorize('delete', $cashdoc);

        $cashdoc->delete();

        return redirect($lang.'/admin/cashdocs')->with('status', 'Запись удалена.');
    }
}