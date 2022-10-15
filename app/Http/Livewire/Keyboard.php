<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Keyboard extends Component
{
    public $keyboardLang = 'ru';
    public $signs;
    // public $inactive = 'en';

    public function mount()
    {
        $this->signs = [
            'ru' => [
                ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ'],
                ['ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', '/'],
                ['translate', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', 'space'],
            ],  
            'en' => [
                ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '-', '+'],
                ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', '"', '|'],
                ['translate', 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '/', 'space'],
            ],
        ];
    }

    public function switchLangs()
    {
        $this->keyboardLang = $this->keyboardLang == 'ru' ? 'en' : 'ru';
    }

    public function render()
    {
        return view('livewire.keyboard');
    }
}
