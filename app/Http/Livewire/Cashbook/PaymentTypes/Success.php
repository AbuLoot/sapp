<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

class Success extends Component
{
    public $lang;

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function backToCash()
    {
        session()->forget('docs');

        return redirect($this->lang.'/cashdesk');
    }

    public function render()
    {
        return view('livewire.cashbook.payment-types.success')
            ->layout('livewire.cashbook.layout');
    }
}
