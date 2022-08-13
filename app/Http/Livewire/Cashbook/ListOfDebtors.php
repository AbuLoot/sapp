<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\User;
use App\Models\Profile;

class ListOfDebtors extends Component
{
    public $lang;
    public $company;

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
    }

    public function render()
    {
        $debtors = Profile::where('is_debtor', 1)->paginate(2);

        return view('livewire.cashbook.list-of-debtors', ['debtors' => $debtors]);
    }
}
