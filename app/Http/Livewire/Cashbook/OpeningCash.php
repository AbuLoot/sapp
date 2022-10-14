<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\CashShiftJournal;

class OpeningCash extends Component
{
    public $company;
    public $cashbook;

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
        $this->cashbook = session()->get('cashbook');
    }

    public function backToDashboard()
    {
        session()->forget('cashbook');
        session()->forget('cashdeskWorkplace');

        return redirect('dashboard');
    }

    public function openTheCash()
    {
        $docType = DocType::where('slug', 'forma-ko-5')->first();

        $lastCloseCashShift = CashShiftJournal::where('cashbook_id', $this->cashbook->id)
            ->where('company_id', $this->company->id)
            ->whereNull('to_user_id')
            ->whereNull('opening_cash_balance')
            ->where('mode', 'close')
            ->orderByDesc('id')
            ->first();

        // Opening The Cash
        $cashShift = new CashShiftJournal;
        $cashShift->cashbook_id = $this->cashbook->id;
        $cashShift->company_id = $this->company->id;
        $cashShift->workplace_id = session()->get('cashdeskWorkplace'); // id
        $cashShift->from_user_id = $lastCloseCashShift->from_user_id ?? null;
        $cashShift->to_user_id = auth()->user()->id;
        $cashShift->opening_cash_balance = $lastCloseCashShift->closing_cash_balance ?? null;
        $cashShift->sum = $lastCloseCashShift->sum ?? null;
        $cashShift->currency = $this->company->currency->code;
        $cashShift->mode = 'open';
        $cashShift->opening_time = date('h:i:s');
        $cashShift->save();

        // Cash Doc
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\CashShiftJournal';
        $cashDoc->order_id = $cashShift->id;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = 0;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->save();

        Cache()->put('openedCash', $cashShift->id);

        return redirect(app()->getLocale().'/cashdesk');
    }

    public function render()
    {
        return view('livewire.cashbook.opening-cash');
    }
}
