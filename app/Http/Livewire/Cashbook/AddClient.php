<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\User;

class AddClient extends Component
{
    public $name;
    public $lastname;
    public $tel;
    public $email;
    public $address;

    protected $rules = [
        'name' => 'required|string|min:2',
        'lastname' => 'required|string|min:2',
        'tel' => 'required|string|min:11',
    ];

    public function mount()
    {
        // $this->user = new User;
    }

    public function save()
    {
        $this->validate();

        $user = new User;
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->tel = $this->tel;
        $user->email = $this->email ?? null;
        $user->password = '';
        $user->address = $this->address;
        $user->save();

        $this->emitUp('newData');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.cashbook.add-client');
    }
}
