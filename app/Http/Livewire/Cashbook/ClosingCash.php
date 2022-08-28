<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\CashShiftJournal;

class ClosingCash extends Component
{
    public $lang;
    public $company;
    public $nominals = [];
    public $attr = [];
    public $key;
    public $number;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        $this->nominals = [
            // Coins
            '5' => null, '10' => null, '20' => null, '50' => null, '100' => null, '200' => null,
            // Banknotes
            '500' => null, '1000' => null, '2000' => null, '5000' => null, '10000' => null, '20000' => null,
        ];

        $this->attr = [
            // Coins
            '5' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            '10' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            '20' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            '50' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            '100' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            '200' => ['color' => 'btn-secondary', '(' => null, ')' => null],
            // Banknotes
            '500' => ['color' => 'btn-warning', '(' => null, ')' => null],
            '1000' => ['color' => 'btn-warning', '(' => null, ')' => null],
            '2000' => ['color' => 'btn-warning', '(' => null, ')' => null],
            '5000' => ['color' => 'btn-warning', '(' => null, ')' => null],
            '10000' => ['color' => 'btn-warning', '(' => null, ')' => null],
            '20000' => ['color' => 'btn-warning', '(' => null, ')' => null],
        ];
    }

    public function setNominal($keyNominal)
    {
        $this->attr[$this->key]['color'] = $this->key > 200 ? 'btn-warning' : 'btn-secondary';

        if (array_key_exists($keyNominal, $this->nominals)) {
            $this->key = $keyNominal;
            $this->attr[$this->key]['color'] = 'btn-success';
        }
    }

    public function setNumber()
    {
        if ($this->number < 0 || !empty($this->number) && !is_numeric($this->number)) {
            $this->addError('error', 'Error');
            return;
        }

        $this->nominals[$this->key] = $this->number;
        $this->attr[$this->key]['color'] = $this->key > 200 ? 'btn-warning' : 'btn-secondary';
        $this->attr[$this->key]['('] = $this->number ? '(' : null;
        $this->attr[$this->key][')'] = $this->number ? ')' : null;

        $this->number = null;
        $this->key = null;
    }

    public function closeTheCash()
    {
        $cashbook = $this->company->cashbooks->first();
        $docType = DocType::where('slug', 'forma-ko-5')->first();

        $lastOpenCashShift = CashShiftJournal::where('cashbook_id', $cashbook->id)
            ->whereNull('to_user_id')
            ->whereNull('closing_cash_balance')
            ->where('mode', 'open')
            ->orderByDesc('id')
            ->first();

        $cashDocs = CashDoc::where('cashbook_id', $cashbook->id)
            ->where('user_id', auth()->user()->id)
            ->where('created_at', '<=', $lastOpenCashShift->created_at)
            ->orderByDesc('id')
            ->get();

        // Opening The Cash
        $lastOpenCashShift = new CashShiftJournal;
        $lastOpenCashShift->from_user_id = null;
        $lastOpenCashShift->to_user_id = auth()->user->id;
        // $cashShift->save();

        // Close The Cash
        $cashShift = new CashShiftJournal;
        $cashShift->cashbook_id = $cashbook->id;
        $cashShift->company_id = $cashbook->company_id;
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
