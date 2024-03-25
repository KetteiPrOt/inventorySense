<?php

namespace App\Livewire\Entities\Products\Edit;

use Livewire\Attributes\Js;
use Livewire\Component;

class SalePricesInput extends Component
{
    private $salePrices;

    public function mount($salePrices)
    {
        $this->salePrices = $salePrices;
    }

    public function render()
    {
        return view('livewire..entities.products.edit.sale-prices-input', [
            'salePrices' => $this->salePrices
        ]);
    }

    #[Js]
    public function pushSalePriceInput()
    {
        return <<<'JS'
            const command = document.getElementById('salePriceInputCommand'),
                  tbody = command.parentElement,
                  newInput = document.getElementById('salePriceInputTemplate').cloneNode(true);
            newInput.id = '';
            newInput.classList.remove('hidden');
            unitsNumber = newInput.firstElementChild.firstElementChild;
            salePrice = newInput.lastElementChild.firstElementChild;
            unitsNumber.name = 'units_numbers[]';
            unitsNumber.required = true;
            salePrice.name = 'sale_prices[]';
            salePrice.required = true;
            tbody.removeChild(command);
            tbody.appendChild(newInput);
            tbody.appendChild(command);
        JS;
    }

    #[Js]
    public function popSalePriceInput()
    {
        return <<<'JS'
            const command = document.getElementById('salePriceInputCommand'),
                  tbody = command.parentElement,
                  input = command.previousElementSibling;
            if(input.id != 'defaultSalePriceInput'){
                tbody.removeChild(input);
            }
        JS;
    }
}
