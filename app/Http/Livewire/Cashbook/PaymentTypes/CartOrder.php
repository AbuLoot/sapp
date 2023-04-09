<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;
use App\Models\IncomingOrder;
use App\Traits\GenerateDocNo;

class CartOrder extends Component
{
    use GenerateDocNo;

    public $lang;
    public $company;
    public $cashbook;
    public $incomingOrderDocNo;
    public $sumOfCart;
    public $currency;
    public $cartProducts;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->company;
        $this->cashbook = session()->get('cashdesk');
        $this->currency = $this->company->currency->symbol;
        $this->incomingOrderDocNo = $this->generateIncomingCashDocNo($this->cashbook->num_id);
        $this->sumOfCart = CashbookIndex::sumOfCart();
    }

    public function render()
    {
        $this->cartProducts = session()->get('cartProducts') ?? [];

        $sortedCartProducts = [];

        foreach($this->cartProducts as $cartProduct) {
            $sortedCartProducts[$cartProduct->time] = $cartProduct;
        }

        krsort($sortedCartProducts);

        return view('livewire.cashbook.payment-types.cart-order', ['sortedCartProducts' => $sortedCartProducts])
            ->layout('livewire.cashbook.layout');
    }
}
