<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;

class Reprint extends Component
{
    public $search = '';
    // public $incomingChecks;

    public function render()
    {
        $incomingChecks = [];

        if (strlen($this->search) >= 2) {
            $incomingChecks = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->paginate(12);
        }

        return view('livewire.cashbook.reprint', ['incomingChecks' => $incomingChecks]);
    }
}
