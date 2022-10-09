<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Keyboard extends Component
{
    public $signs;
    public $lang = 'ru';
    // public $inactive = 'en';

    public function mount()
    {
        $this->signs = [
            'ru' => [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ'],
                ['ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', '/'],
                ['translate', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', ' '],
            ],  
            'en' => [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
                ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', '/'],
                ['translate', 'z', 'x', 'c', 'v', 'b', 'n', 'm', ' '],
            ],
        ];
    }

    public function switchLangs()
    {
        $this->lang = $this->lang == 'ru' ? 'en' : 'ru';
        // $this->inactive = $this->lang == 'en' ? 'ru' : 'en';
    }

    public function render()
    {
        return view('livewire.keyboard');
    }
}
