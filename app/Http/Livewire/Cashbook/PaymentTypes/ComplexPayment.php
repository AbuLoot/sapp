<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;
use App\Http\Livewire\Cashbook\PaymentTypes;
use App\Http\Livewire\Cashbook\PaymentTypes\PaymentTypesIndex;

use App\Models\PaymentType;

class ComplexPayment extends Component
{
    public $cash = null;
    public $company;
    public $sumOfCart;
    public $payButton = false;
    public $paymentType;
    public $paymentTypes;
    public $complexPayments = [];
    public $cashPayment;
    public $bankCard;
    public $onKaspi;

    protected $rules = [
        'complexPayments' => 'array|max:2',
        'cashPayment' => 'integer|min:2',
        'bankCard' => 'integer|min:2',
        'onKaspi' => 'integer|min:2'
    ];

    public function mount()
    {
        $this->sumOfCart = Index::sumOfCart();
        $this->company = auth()->user()->profile->company;
        $this->paymentType = PaymentType::where('slug', 'complex-payment')->first();
        $this->paymentTypes = PaymentType::whereIn('slug', ['cash-payment', 'bank-card', 'on-kaspi'])->get();
    }

    public function updated($key, $value)
    {
        $sum = 0;

        foreach($this->complexPayments as $payment) {
            $sum += $this->{"$payment"};
        }

        $this->payButton = ($sum == $this->sumOfCart['sumDiscounted']) ? true : false;
    }

    public function pay()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['type'] = $this->paymentType->slug;

        foreach($this->complexPayments as $payment) {
            $paymentDetail['types'][$payment] = $this->{"$payment"};
        }

        // $paymentDetail['sum'] = $this->sumOfCart['sumDiscounted'];

        // PaymentTypesIndex::makeDocs($paymentDetail);
        
        $cashbook = session()->get('cashbook');

        dd(PaymentTypesIndex::generateCashDocNo($cashbook->id));
        // $this->emit('makeDocs', $paymentDetail);
    }

    public function render()
    {
        return view('livewire.cashbook.payment-types.complex-payment')
            ->layout('livewire.cashbook.layout');
    }
}
