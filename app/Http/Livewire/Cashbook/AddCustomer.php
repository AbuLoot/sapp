<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\User;
use App\Models\Profile;

class AddCustomer extends Component
{
    public $name;
    public $lastname;
    public $tel;
    public $email;
    public $address;

    protected $listeners = [
        'addCustomerInput',
    ];

    protected $rules = [
        'name' => 'required|min:2',
        'lastname' => 'required|min:2',
        'tel' => 'required|min:11',
    ];

    public function addCustomerInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
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
        $user->is_customer = 1;
        $user->save();

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->region_id = 1;
        $profile->save();
 
        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Запись добавлена', 'selector' => 'closeAddCustomer'
        ]);
    }

    public function render()
    {
        return view('livewire.cashbook.add-customer');
    }
}
