<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Currency;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', BankAccount::class);

        $bank_accounts = BankAccount::paginate(50);

        return view('pos.bank_accounts.index', compact('bank_accounts'));
    }

    public function create($lang)
    {
        // $this->authorize('create', BankAccount::class);
        $currencies = Currency::orderBy('sort_id')->get();

        return view('pos.bank_accounts.create', ['currencies' => $currencies]);
    }

    public function store(Request $request)
    {
        // $this->authorize('create', BankAccount::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:bank_accounts',
        ]);

        $company = auth()->user()->profile->company->first();

        $bank_account = new BankAccount;
        $bank_account->company_id = $company->id;
        $bank_account->title = $request->title;
        $bank_account->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $bank_account->account_number = $request->account_number;
        $bank_account->bic = $request->bic;
        $bank_account->balance = $request->balance;
        $bank_account->currency = $request->currency;
        $bank_account->comment = $request->comment;
        $bank_account->save();

        return redirect($request->lang.'/pos/bank_accounts')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        $currencies = Currency::orderBy('sort_id')->get();

        // $this->authorize('update', $bank_account);

        return view('pos.bank_accounts.edit', compact('currencies', 'bank_account'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $bank_account = BankAccount::findOrFail($id);

        // $this->authorize('update', $bank_account);

        // $bank_account->company_id = $company->id;
        $bank_account->title = $request->title;
        $bank_account->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $bank_account->account_number = $request->account_number;
        $bank_account->bic = $request->bic;
        $bank_account->balance = $request->balance;
        $bank_account->currency = $request->currency;
        $bank_account->comment = $request->comment;
        // $bank_account->status = ($request->status == 'on') ? 1 : 0;
        $bank_account->save();

        return redirect($lang.'/pos/bank_accounts')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $bank_account = BankAccount::find($id);

        // $this->authorize('delete', $bank_account);

        $bank_account->delete();

        return redirect($lang.'/pos/bank_accounts')->with('status', 'Запись удалена.');
    }
}