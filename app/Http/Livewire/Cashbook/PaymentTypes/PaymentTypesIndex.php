<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;

use App\Models\Product;
use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

class PaymentTypesIndex extends Component
{
    public $lang;
    public $company;
    public $sumOfCart;
    public $cartProducts;
    public $paymentTypes;

    protected $listeners = ['makeDocs'];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        if (empty(session()->get('cartProducts'))) {
            return redirect($this->lang.'/cashdesk');
        }

        if (empty(session()->get('cashbook'))) {
            session()->put('cashbook', $this->company->cashbooks->first());
        }

        $this->sumOfCart = CashbookIndex::sumOfCart();
        $this->paymentTypes = PaymentType::get();
    }

    public function backToCash()
    {
        session()->forget('docs');

        return redirect($this->lang.'/cashdesk');
    }

    public function render()
    {
        $this->cartProducts = session()->get('cartProducts') ?? [];

        return view('livewire.cashbook.payment-types.payment-types-index')
            ->layout('livewire.cashbook.layout');
    }
}
