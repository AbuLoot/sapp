<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;
use App\Http\Livewire\Cashbook\PaymentTypes;

use App\Models\PaymentType;

class OnKaspi extends Component
{
    public $cash = null;
    public $sumOfCart;
    public $payButton = false;

    public function mount()
    {
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'on-kaspi')->first();
    }

    public function pay()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['typeSlug'] = $this->paymentType->slug;
        $paymentDetail['sum'] = $this->sumOfCart['sumDiscounted'];

        $this->emitUp('makeDocs', $paymentDetail);
    }

    public function render()
    {
        // return view('livewire.cashbook.payment-types.on-kaspi');
    }
}
