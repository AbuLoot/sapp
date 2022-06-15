<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Category;

class AddCategory extends Component
{
    public $category;
    public $category_id;
    public $alert = false;

    protected $rules = [
        'category.title' => 'required|string|min:2',
    ];

    public function mount()
    {
        $this->category = new Category;
    }

    public function saveCategory()
    {
        $data = $this->validate()['category'];

        $lastCategory = Category::orderByDesc('id')->first();

        $category = new Category;
        $category->sort_id = $lastCategory->id + 1;
        $category->slug = Str::slug($data['title']);
        $category->title = $data['title'];
        $category->lang = 'ru';
        $category->status = 1;
        $category->save();

        if (is_null($this->category_id)) {
            $category->saveAsRoot();
        }
        else {
            $parentNode = Category::find($this->category_id);
            $category->appendToNode($parentNode)->save();
        }

        $this->emitUp('newData');
        session()->flash('message', 'Запись добавлена.');
        // $this->dispatchBrowserEvent('hide-modal');
    }

    public function render()
    {
        $categories = Category::orderBy('sort_id')->get()->toTree();

        return view('livewire.store.add-category', ['categories' => $categories]);
    }
}
