<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\CashShiftJournal;

class ClosingCash extends Component
{
    public $company;
    public $nominals = [];
    public $attr = [];
    public $key;
    public $number;

    protected $listeners = [
        'closingCashInput',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
        $this->cashbook = session()->get('cashbook');

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

    public function closingCashInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function closeTheCash()
    {
        $sum = 0;

        foreach($this->nominals as $amount => $quantity) {
            $sum += $amount * $quantity;
        }

        // Close The Cash
        $lastOpenCashShift = CashShiftJournal::find(Cache::get('openedCash'));
        $lastOpenCashShift->from_user_id = auth()->user()->id;
        $lastOpenCashShift->closing_cash_balance = $sum;
        $lastOpenCashShift->banknotes_and_coins = json_encode($this->nominals);
        $lastOpenCashShift->sum = $sum;
        $lastOpenCashShift->mode = 'close';
        $lastOpenCashShift->closing_time = date('h:i:s');
        $lastOpenCashShift->save();

        // Cash Doc
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\CashShiftJournal';
        $cashDoc->order_id = $lastOpenCashShift->id;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = 0;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->save();

        Cache::forget('openedCash');

        return redirect('apps');
    }

    public function render()
    {
        return view('livewire.cashbook.closing-cash');
    }
}
