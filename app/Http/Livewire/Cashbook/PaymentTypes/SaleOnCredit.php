<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;
use App\Models\PaymentType;
use App\Models\User;
use App\Models\Profile;

class SaleOnCredit extends Component
{
    protected $queryString = ['search'];

    public $search = '';
    public $sumOfCart;
    public $name;
    public $lastname;
    public $tel;
    public $email;
    public $address;

    protected $rules = [
        'name' => 'required|min:2',
        'lastname' => 'required|min:2',
        'tel' => 'required|min:11',
    ];

    public function mount()
    {
        $this->sumOfCart = CashbookIndex::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'sale-on-credit')->first();
    }

    public function openModal()
    {
        $this->dispatchBrowserEvent('client-form', ['formMode' => 'yes!']);
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

        session()->flash('message', 'Запись добавлена');

        // $this->emitUp('newData');
        $this->dispatchBrowserEvent('close-modal');
        // $this->dispatchBrowserEvent('client-form', ['formMode' => false]);
    }

    public function debtorIs($id)
    {
        $user = User::findOrFail($id);
    
        if (empty($user->profile)) {

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->region_id = 1;
            $profile->is_debtor = true;
            $profile->debt_sum = $this->sumOfCart['sumDiscounted'];
            $profile->save();
        } else {
            $user->profile->is_debtor = true;
            $user->profile->debt_sum = $user->profile->debt_sum + $this->sumOfCart['sumDiscounted'];
            $user->profile->save();
        }

        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['type'] = $this->paymentType->slug;
        $paymentDetail['user_id'] = $user->id;
        $paymentDetail['dept_sum'] = $this->sumOfCart['sumDiscounted'];
        $paymentDetail['incoming_order'] = null;
        $paymentDetail['outgoing_doc'] = null;

        $this->emitTo('makeDocs', $paymentDetail);

        // session()->forget('cartProducts');
        // return redirect($this->lang.'/cashdesk/payment_type/success');
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

        return view('livewire.cashbook.payment-types.sale-on-credit', ['clients' => $clients])
            ->layout('livewire.cashbook.layout');
    }
}
