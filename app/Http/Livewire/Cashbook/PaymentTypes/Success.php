<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

class Success extends Component
{
    public function render()
    {
        return view('livewire.cashbook.payment-types.success')
            ->layout('livewire.cashbook.layout');
    }
}
