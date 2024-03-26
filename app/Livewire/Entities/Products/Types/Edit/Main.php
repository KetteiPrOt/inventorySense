<?php

namespace App\Livewire\Entities\Products\Types\Edit;

use App\Models\Products\Type;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Main extends Component
{
    #[Locked]
    public Type $type;

    public $name;

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:49',
                Rule::unique('product_types', 'name')->ignore($this->type->id)
            ]
        ];
    }

    public function  validationAttributes()
    {
        return ['name' => 'nombre'];
    }

    public function mount(Type $type)
    {
        $this->type = $type;
        $this->name = $type->name;
    }

    public function render()
    {
        return view('livewire..entities.products.types.edit.main');
    }

    public function update()
    {
        $this->validate();
        $this->type->update(['name' => mb_strtoupper($this->name)]);
        $this->name = $this->type->name;
        $this->dispatch('type-updated');
    }
}
