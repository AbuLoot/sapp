<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Keyboard extends Component
{
    public $keyboardLang = 'ru';
    public $letterCase = 'lc';
    public $signs;

    public function mount()
    {
        $this->signs = [
            'ru' => [
                'lc' => [
                    ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ'],
                    ['uc', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э'],
                    ['translate', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', 'space'],
                ],
                'uc' => [
                    ['Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ'],
                    ['lc', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э'],
                    ['translate', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю', 'space'],
                ]
            ],
            'en' => [
                'lc' => [
                    ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '-', '+'],
                    ['uc', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', '"'],
                    ['translate', 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.', 'space'],
                ],
                'uc' => [
                    ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', '-', '+'],
                    ['lc', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', ';', '"'],
                    ['translate', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', ',', '.', 'space'],
                ]
            ],
        ];
    }

    public function upperCase()
    {
        $this->letterCase = 'uc';
    }

    public function lowerCase()
    {
        $this->letterCase = 'lc';
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
