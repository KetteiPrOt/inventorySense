<?php

namespace App\Livewire\Entities\Products\Presentations\Edit;

use App\Models\Products\Presentation;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class Main extends Component
{
    #[Locked]
    public ?int $page = null;

    #[Locked]
    public ?string $search = null;

    #[Locked]
    public Presentation $presentation;

    public $content;

    public function rules()
    {
        return [
            'content' => [
                'required', 'integer', 'min:1', 'max:65000',
                Rule::unique('product_presentations', 'content')->ignore($this->presentation->id)
            ]
        ];
    }

    public function  validationAttributes()
    {
        return ['content' => 'contenido'];
    }

    public function mount(Presentation $presentation, ?int $page, ?string $search)
    {
        $this->presentation = $presentation;
        $this->content = $presentation->content;
        $this->$page = $page;
        $this->search = $search;
    }

    public function render()
    {
        return view('livewire..entities.products.presentations.edit.main');
    }

    public function update()
    {
        $this->validate();
        $this->presentation->update(['content' => $this->content]);
        $this->dispatch('presentation-updated');
        return redirect()->route('product-presentations.index', [
            'page' => $this->page,
            'search' => $this->search
        ]);
    }
}
