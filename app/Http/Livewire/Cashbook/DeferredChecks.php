<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class DeferredChecks extends Component
{
    protected $queryString = ['search'];

    public $search = '';
    public $deferredChecks = [];

    protected $listeners = ['newData', '$refresh'];

    public function mount()
    {
        if (Cache::has('deferredChecks')) {
            $this->deferredChecks = Cache::get('deferredChecks');
        }
    }

    public function returnCheck($orderName)
    {
        $this->emitUp('returnDeferredCheck', $orderName);
    }

    public function returnDeferredCheck($orderName)
    {
        if (Cache::has('deferredChecks')) {
            $deferredChecks = Cache::get('deferredChecks');
        }

        $deferredCheck = $deferredChecks[$orderName];

        session()->forget('cartProducts');
        $this->cartProducts = [];
        session()->put('cartProducts', $deferredCheck['cart']);
    }

    public function removeFromDeferred($orderName)
    {
        $deferredChecks = Cache::get('deferredChecks');

        if (count($deferredChecks) == 1) {
            Cache::forget('deferredChecks');
        }

        unset($deferredChecks[$orderName]);
        Cache::put('deferredChecks', $deferredChecks);
    }

    public function render()
    {
        $checks = [];

        if (strlen($this->search) >= 2) {
            // $checks = $this->deferredChecks->where();
        }

        return view('livewire.cashbook.deferred-checks', ['checks' => $checks]);
    }
}
