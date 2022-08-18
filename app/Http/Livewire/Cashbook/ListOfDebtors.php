<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;
use App\Models\Profile;

class ListOfDebtors extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $debtor;
    public $repaymentAmount;

    protected $rules = [
        'debtor' => 'required|numeric|min:2',
        'repaymentAmount' => 'required|numeric',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
    }

    public function repayFor($debtor_id)
    {
        $this->debtor = Profile::find($debtor_id);

        $this->dispatchBrowserEvent('toggle-modal');
    }

    public function repay()
    {
        dd($this->repaymentAmount, $this->debtor);
    }

    public function render()
    {
        $debtors = Profile::where('is_debtor', 1)->paginate(2);

        return view('livewire.cashbook.list-of-debtors', ['debtors' => $debtors]);
    }
}
