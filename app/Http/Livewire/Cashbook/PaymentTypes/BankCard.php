<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;
use App\Http\Livewire\Cashbook\PaymentTypes;

use App\Models\PaymentType;

class BankCard extends Component
{
    public $cash = null;
    public $change = 0;
    public $sumOfCart;
    public $payButton = false;

    public function mount()
    {
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'cash-payment')->first();
    }

    public function updated($key, $value)
    {
        if (strlen($this->sumOfCart['sumDiscounted']) <= strlen($this->cash)) {
            $this->change = (int) $this->cash - $this->sumOfCart['sumDiscounted'];
            $this->payButton = true;
        } else {
            $this->payButton = false;
        }
    }

    public function pay()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['cash'] = $this->cash;
        $paymentDetail['change'] = $this->change;

        $this->emitUp('makeDocs', $paymentDetail);
    }

    public function render()
    {
        return view('livewire.cashbook.payment-types.cash-payment');
    }
}
