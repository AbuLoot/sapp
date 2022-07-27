<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class DeferredChecks extends Component
{
    public $deferredChecks = [];

    public function mount()
    {
        $this->deferredChecks = Cache::get('deferredChecks');
    }

    public function render()
    {
        return view('livewire.cashbook.deferred-checks');
    }
}
