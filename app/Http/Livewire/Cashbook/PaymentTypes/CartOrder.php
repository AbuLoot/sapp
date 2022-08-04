<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;

use App\Models\IncomingOrder;

class CartOrder extends Component
{
    public $lang;
    public $company;
    public $docNo;
    public $sumOfCart;
    public $currency;
    public $cartProducts;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->currency = $this->company->currency->symbol;
        $this->docNo = $this->generateCashDocNo(session()->get('cashbook')->id);
        $this->sumOfCart = CashbookIndex::sumOfCart();
    }

    public function generateCashDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateCashDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function render()
    {
        $this->cartProducts = session()->get('cartProducts') ?? [];

        return view('livewire.cashbook.payment-types.cart-order')
            ->layout('livewire.cashbook.layout');
    }
}
