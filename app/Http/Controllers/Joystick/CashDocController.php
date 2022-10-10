<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
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

        return view('joystick.cashdocs.index', compact('cashdocs'));
    }

    public function show($lang, $id)
    {
        $cashdoc = CashDoc::findOrFail($id);

        return view('joystick.cashdocs.show', compact('cashdoc'));
    }

    public function destroy($lang, $id)
    {
        $cashdoc = CashDoc::find($id);

        // $this->authorize('delete', $cashdoc);

        $cashdoc->delete();

        return redirect($lang.'/admin/cashdocs')->with('status', 'Запись удалена.');
    }
}