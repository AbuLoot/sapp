<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;

class Reprint extends Component
{
    public $search = '';
    // public $incomingOrders;

    public function render()
    {
        $incomingOrders = [];

        if (strlen($this->search) >= 2) {
            $incomingOrders = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->paginate(12);
        }

        return view('livewire.cashbook.reprint', ['incomingOrders' => $incomingOrders]);
    }
}
