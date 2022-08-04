<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\User;

class ListOfDeptors extends Component
{
    public $lang;

    public function render()
    {
        return view('livewire.cashbook.list-of-deptors');
    }
}
