<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class DeferredChecks extends Component
{
    public $company;
    public $deferredChecks = [];

    public function returnCheck($orderName)
    {
        $this->emitUp('returnDeferredCheck', $orderName);
    }

    public function removeFromDeferred($orderName)
    {
        $ccid = $this->company->id.session('cashdesk')->id;

        $deferredChecks = Cache::get('deferredChecks'.$ccid);

        if (count($deferredChecks) == 1) {
            Cache::forget('deferredChecks'.$ccid);
        }

        unset($deferredChecks[$orderName]);
        Cache::put('deferredChecks'.$ccid, $deferredChecks);

        $this->dispatchBrowserEvent('show-toast', ['reload' => true]);
    }

    public function render()
    {
        $ccid = $this->company->id.session('cashdesk')->id;

        $this->deferredChecks = Cache::has('deferredChecks'.$ccid)
            ? Cache::get('deferredChecks'.$ccid)
            : [];

        return view('livewire.cashbook.deferred-checks');
    }
}
