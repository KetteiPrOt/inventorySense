<?php

namespace App\Livewire\Entities\Products\Presentations\Create;

use App\Models\Products\Presentation;
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

    public $content;

    public function rules()
    {
        return ['content' => 'required|integer|min:1|max:65000|unique:product_presentations,content'];
    }

    public function  validationAttributes()
    {
        return ['content' => 'contenido'];
    }

    public function render()
    {
        return view('livewire..entities.products.presentations.create.main');
    }

    public function create()
    {
        $this->validate();
        Presentation::create(['content' => $this->content]);
        $this->dispatch('presentation-created');
        $this->reset('content');
        return redirect()->route('product-presentations.index', [
            'page' => $this->page,
            'search' => $this->search
        ]);
    }
}
