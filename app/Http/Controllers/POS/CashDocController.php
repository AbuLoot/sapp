<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use App\Models\CashDoc;
use App\Models\Product;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;
use App\Models\CashShiftJournal;

class CashDocController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', CashDoc::class);

        $cashdocs = CashDoc::orderByDesc('id')->paginate(50);

        return view('pos.cashdocs.index', compact('cashdocs'));
    }

    public function show($lang, $id)
    {
        $cashdoc = CashDoc::findOrFail($id);

        return view('pos.cashdocs.show', compact('cashdoc'));
    }

    public function destroy($lang, $id)
    {
        $cashdoc = CashDoc::find($id);

        // $this->authorize('delete', $cashdoc);

        $cashdoc->delete();

        return redirect($lang.'/pos/cashdocs')->with('status', 'Запись удалена.');
    }
}