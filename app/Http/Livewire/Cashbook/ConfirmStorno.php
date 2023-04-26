<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Http\Livewire\Cashbook\Index;

class ConfirmStorno extends Component
{
    public $code;
    public $cashbook;
    public $productId;

    protected $listeners = [
        'confirmStornoInput',
        'showStornoModal',
    ];

    protected $rules = [
        'code' => 'required|min:8|max:12',
    ];

    public function mount()
    {
        $this->cashbook = session()->get('cashdesk');
    }

    public function confirmStornoInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function confirm()
    {
        $values = explode('/', $this->cashbook->description);

        $this->validate([
            'code' => ['required', 'integer', 'min:8', Rule::in($values)],
        ]);

        $this->dispatchBrowserEvent('hide-storno-modal');
        $this->code = null;
        $this->emitUp('removeFromCart', $this->productId);
    }

    public function showStornoModal($id)
    {
        $this->productId = $id;
        $this->dispatchBrowserEvent('show-storno-modal');
    }

    public function render()
    {
        return view('livewire.cashbook.confirm-storno');
    }
}
