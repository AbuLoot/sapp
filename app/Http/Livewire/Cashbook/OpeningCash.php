<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\CashShiftJournal;

class OpeningCash extends Component
{
    public $lang;
    public $company;
    public $cashbook;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->cashbook = $this->company->cashbooks->first();
    }

    public function openTheCash()
    {
        $docType = DocType::where('slug', 'forma-ko-5')->first();

        $lastCloseCashShift = CashShiftJournal::where('cashbook_id', $this->cashbook->id)
            ->whereNull('from_user_id')
            ->whereNull('opening_cash_balance')
            ->where('mode', 'close')
            ->orderByDesc('id')
            ->first();

        $cashDocs = CashDoc::where('cashbook_id', $this->cashbook->id)
            ->where('user_id', auth()->user()->id)
            ->where('created_at', '<=', $lastCloseCashShift->created_at)
            ->orderByDesc('id')
            ->get();

        // Opening The Cash
        $lastCloseCashShift = new CashShiftJournal;
        $lastCloseCashShift->from_user_id = null;
        $lastCloseCashShift->to_user_id = null;
        // $cashShift->save();

        // Close The Cash
        $cashShift = new CashShiftJournal;
        $cashShift->cashbook_id = $this->cashbook->id;
        $cashShift->company_id = $this->cashbook->company_id;
        $cashShift->from_user_id = auth()->user()->id;
        $cashShift->to_user_id = null;
        $cashShift->opening_cash_balance = null;
        $cashShift->closing_cash_balance = null;
        $cashShift->banknotes_and_coins = json_encode($this->nominals);
        $cashShift->sum = null;
        $cashShift->currency = $this->company->currency->code;
        $cashShift->mode = 'close';
        $cashShift->shift_time = null;
        // $cashShift->save();
    }

    public function render()
    {
        return view('livewire.cashbook.closing-cash');
    }
}
