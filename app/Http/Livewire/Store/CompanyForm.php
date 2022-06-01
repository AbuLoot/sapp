<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Company;

class CompanyForm extends Component
{
    public $company;
    public $is_supplier = 1;
    public $is_customer;
    public $alert = false;

    protected $rules = [
        'company.title' => 'required|string|min:6',
        'company.phones' => 'required|string',
        'company.legal_address' => 'required|string'
    ];

    public function mount()
    {
        $this->company = new Company;
    }

    public function saveCompany()
    {
        $data = $this->validate()['company'];

        $company = Company::orderByDesc('id')->first();

        // $company = new Company();
        $this->company->sort_id = $company->id + 1;
        $this->company->region_id = 0;
        $this->company->slug = Str::slug($data['title']);
        $this->company->title = $data['title'];
        $this->company->phones = $data['phones'];
        $this->company->legal_address = $data['legal_address'];
        $this->company->image = 'no-image-mini.png';
        $this->company->is_supplier = ($this->is_supplier) ? 1 : 0;
        $this->company->is_customer = ($this->is_customer) ? 1 : 0;
        $this->company->status = 1;
        $this->company->save();

        // Company::create([
        //     'sort_id' => $companies_count + 1,
        //     'region_id' => 0,
        //     'slug' => Str::slug($this->company->title),
        //     'title' => $this->company->title,
        //     'phones' => $this->company->phones,
        //     'legal_address' => $this->company->address,
        //     'image' => 'no-image-mini.png',
        //     'is_supplier' => ($this->is_supplier) ? 1 : 0,
        //     'is_customer' => ($this->is_customer) ? 1 : 0,
        //     'status' => 1,
        // ]);
 
        // $this->alert = true;
    }

    public function render()
    {
        return view('livewire.store.company-form');
    }
}
