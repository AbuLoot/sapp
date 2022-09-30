<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class DeferredChecks extends Component
{
    public $deferredChecks = [];

    public function returnCheck($orderName)
    {
        $this->emitUp('returnDeferredCheck', $orderName);
    }

    public function removeFromDeferred($orderName)
    {
        $deferredChecks = Cache::get('deferredChecks');

        if (count($deferredChecks) == 1) {
            Cache::forget('deferredChecks');
        }

        unset($deferredChecks[$orderName]);
        Cache::put('deferredChecks', $deferredChecks);

        $this->dispatchBrowserEvent('show-toast', ['reload' => true]);
    }

    public function render()
    {
        $this->deferredChecks = Cache::has('deferredChecks')
            ? Cache::get('deferredChecks')
            : [];

        return view('livewire.cashbook.deferred-checks');
    }
}
