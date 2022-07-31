<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Models\User;

class SaleOnCredit extends Component
{
    protected $queryString = ['search'];

    public $search = '';
    public $name;
    public $lastname;
    public $tel;
    public $email;
    public $address;

    protected $rules = [
        // 'name' => 'required|string|min:2',
        // 'lastname' => 'required|string|min:2',
        // 'tel' => 'required|string|min:11',
    ];

    public function openModal()
    {
        $this->dispatchBrowserEvent('client-form', ['formMode' => 'yes!']);
        // dd(123);
    }

    public function save()
    {
        // $data = $this->validate();
        // dd($data);
        $this->dispatchBrowserEvent('client-form', ['formMode' => false]);

        // dd($data);

        // $user = new User;
        // $user->name = $this->name;
        // $user->lastname = $this->lastname;
        // $user->tel = $this->tel;
        // $user->email = $this->email ?? null;
        // $user->password = '';
        // $user->address = $this->address;
        // $user->save();

        // session()->flash('message', 'Запись добавлена');

        // $this->emitUp('newData');
        // $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $clients = [];

        if (strlen($this->search) >= 2) {
            $clients = User::where('name', 'like', $this->search.'%')
                ->orWhere('lastname', 'like', $this->search.'%')
                ->orWhere('tel', 'like', $this->search.'%')
                ->get()
                ->take(7);
        }

        return view('livewire.cashbook.payment-types.sale-on-credit', ['clients' => $clients]);
    }
}
