<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;

use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\IncomingOrder;

class PaymentTypes extends Component
{
    public $lang;
    public $view;
    public $company;
    public $cashbook;
    public $docNo;
    public $cartProducts;
    public $paymentTypes;
    public $totalDiscount;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->view = false;
        $this->company = auth()->user()->profile->company;
        $this->cashbook = $this->company->cashbooks->first();
        $this->docNo = $this->generateDocNo($this->cashbook->id);
        $this->paymentTypes = PaymentType::get();
    }

    public function paymentType($slug)
    {
        $this->view = trim(strip_tags($slug));
    }

    public function generateDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::where('doc_no', 'like', $cashbook_id.'/_')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function backToCash()
    {
        session()->forget('cartProducts');

        return redirect($this->lang.'/cashdesk');
    }

    public function render()
    {
        $sumOfCart = Index::sumOfCart();
        $this->cartProducts = session()->get('cartProducts') ?? [];

        return view('livewire.cashbook.payment-types', ['sumOfCart' => $sumOfCart])
            ->layout('livewire.cashbook.layout');
    }
}
