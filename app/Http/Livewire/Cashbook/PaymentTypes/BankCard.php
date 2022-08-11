<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;
use App\Http\Livewire\Cashbook\PaymentTypes;

use App\Models\PaymentType;

class BankCard extends Component
{
    public $lang;
    public $cash = null;
    public $sumOfCart;
    public $payButton = false;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'bank-card')->first();
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['cash'] = $this->cash;

        return redirect($this->lang.'/cashdesk/payment-type/success');
    }

    public function pay()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['cash'] = $this->cash;

        $this->emitUp('makeDocs', $paymentDetail);
    }

    public function render()
    {
        // return view('livewire.cashbook.payment-types.bank-card')
            // ->layout('livewire.cashbook.layout');
    }
}
