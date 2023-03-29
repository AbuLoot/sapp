<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use App\Models\CashDoc;
use App\Models\Product;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;
use App\Models\CashShiftJournal;
use App\Models\DocType;

class CashDocController extends Controller
{
    public $companyId;

    public function index()
    {
        // $this->authorize('viewAny', CashDoc::class);

        $cashDocs = CashDoc::orderByDesc('id')->where('company_id', $this->companyId)->paginate(50);
        $cashDocType = DocType::where('slug', 'forma-ko-5')->first();
        $company = auth()->user()->profile->company;
        $currency = $company->currency->symbol ?? null;

        return view('pos.cashdocs.index', compact('cashDocs', 'cashDocType', 'currency'));
    }

    public function show($lang, $id)
    {
        $cashDoc = CashDoc::where('company_id', $this->companyId)->findOrFail($id);

        return view('pos.cashdocs.show', compact('cashDoc'));
    }

    public function destroy($lang, $id)
    {
        $cashDoc = CashDoc::where('company_id', $this->companyId)->find($id);

        // $this->authorize('delete', $cashDoc);

        $cashDoc->delete();

        return redirect($lang.'/pos/cashdocs')->with('status', 'Запись удалена.');
    }
}