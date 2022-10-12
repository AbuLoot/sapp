<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\DocType;

class DocTypeController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', DocType::class);

        $doc_types = DocType::paginate(50);

        return view('pos.doc_types.index', compact('doc_types'));
    }

    public function create($lang)
    {
        // $this->authorize('create', DocType::class);

        return view('pos.doc_types.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', DocType::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:255|unique:doc_types',
        ]);

        $doc_type = new DocType;

        $doc_type->type = $request->type;
        $doc_type->title = $request->title;
        $doc_type->slug = (empty($request->slug)) ? Str::slug($request->type) : $request->slug;
        $doc_type->description = $request->description;
        $doc_type->lang = $request->lang;
        $doc_type->save();

        return redirect($request->lang.'/pos/doc_types')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $doc_type = DocType::findOrFail($id);

        // $this->authorize('update', $doc_type);

        return view('pos.doc_types.edit', compact('doc_type'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:255',
        ]);

        $doc_type = DocType::findOrFail($id);

        // $this->authorize('update', $doc_type);

        $doc_type->type = $request->type;
        $doc_type->title = $request->title;
        $doc_type->slug = (empty($request->slug)) ? Str::slug($request->type) : $request->slug;
        $doc_type->description = $request->description;
        $doc_type->lang = $request->lang;
        $doc_type->save();

        return redirect($lang.'/pos/doc_types')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $doc_type = DocType::find($id);

        // $this->authorize('delete', $doc_type);

        $doc_type->delete();

        return redirect($lang.'/pos/doc_types')->with('status', 'Запись удалена.');
    }
}