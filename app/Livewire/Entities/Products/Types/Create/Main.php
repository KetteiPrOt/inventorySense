<?php

namespace App\Livewire\Entities\Products\Types\Create;

use App\Models\Products\Type;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Main extends Component
{
    #[Locked]
    public ?int $page = null;

    #[Locked]
    public ?string $search = null;

    public function mount(?int $page, ?string $search)
    {
        $this->$page = $page;
        $this->search = $search;
    }

    public $name;

    public function rules()
    {
        return ['name' => 'required|string|min:2|max:49|unique:product_types,name'];
    }

    public function  validationAttributes()
    {
        return ['name' => 'nombre'];
    }

    public function render()
    {
        return view('livewire..entities.products.types.create.main');
    }

    public function create()
    {
        $this->validate();
        Type::create(['name' => mb_strtoupper($this->name)]);
        $this->dispatch('type-created');
        $this->reset('name');
        return redirect()->route('product-types.index', [
            'page' => $this->page,
            'search' => $this->search
        ]);
    }
}
