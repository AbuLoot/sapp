<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Category;

class AddCategory extends Component
{
    public $company;
    public $category;
    public $categoryId;
    public $alert = false;

    protected $rules = [
        'category.title' => 'required|min:2',
    ];

    public function mount()
    {
        $this->category = new Category;
        $this->company = auth()->user()->company;
    }

    public function saveCategory()
    {
        $data = $this->validate()['category'];

        $category = new Category;
        $category->company_id = $this->company->id;
        $category->slug = Str::slug($data['title']);
        $category->title = $data['title'];
        $category->lang = 'ru';
        $category->status = 1;
        $category->save();

        if (is_null($this->categoryId)) {
            $category->saveAsRoot();
        }
        else {
            $parentNode = Category::find($this->categoryId);
            $category->appendToNode($parentNode)->save();
        }

        $this->category = null;
        $this->emitUp('newData');
        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Запись добавлена', 'selector' => 'closeAddCategory'
        ]);
    }

    public function render()
    {
        $categories = Category::where('company_id', $this->company->id)->orderBy('sort_id')->get()->toTree();

        return view('livewire.store.add-category', ['categories' => $categories]);
    }
}
