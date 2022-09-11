<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Company;

class AddCompany extends Component
{
    public $company;
    public $isSupplier = 1;
    public $isCustomer;
    public $alert = false;

    protected $rules = [
        'company.title' => 'required|min:2',
        'company.phones' => '',
        'company.legalAddress' => ''
    ];

    public function mount()
    {
        $this->company = new Company;
    }

    public function saveCompany()
    {
        $data = $this->validate()['company'];

        $lastCompany = Company::orderByDesc('id')->first();

        Company::create([
            'sort_id' => (int) $lastCompany->id + 1,
            'region_id' => 0,
            'slug' => Str::slug($data['title']),
            'title' => $data['title'],
            'phones' => $data['phones'] ?? null,
            'legal_address' => $data['legalAddress'] ?? null,
            'image' => 'no-image-mini.png',
            'is_supplier' => ($this->isSupplier) ? 1 : 0,
            'is_customer' => ($this->isCustomer) ? 1 : 0,
            'status' => 1,
        ]);

        $this->emitUp('newData');
        // $this->dispatchBrowserEvent('close-modal', ['addCompany' => 'addCompany']);
    }

    public function render()
    {
        return view('livewire.store.add-company');
    }
}
