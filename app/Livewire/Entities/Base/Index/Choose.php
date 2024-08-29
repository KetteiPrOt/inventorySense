<?php

namespace App\Livewire\Entities\Base\Index;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

abstract class Choose extends Component
{
    use WithPagination;

    /**
     * Show "all" between the options of the choose component
     */
    #[Locked]
    public ?bool $allOption = null;

    /**
     * Show all the entities by default in the options
     */
    #[Locked]
    public bool $showAllByDefault = true;

    /**
     * Places "required" attribute in all options
     */
    #[Locked]
    public bool $required = true;

    /**
     * Specifies the entitie selected by default. Can be the string 'all'.
     */
    protected null|int|string $selectedByDefault = null;

    /**
     * Input to search a entitie.
     */
    public $search = null;

    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = null;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = null;

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = null;

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = null;

    public function mount(
        bool $showAllByDefault = true,
        null|int|string $selectedByDefault = null,
        bool $required = true,
        bool $allOption = false
    )
    {
        $this->showAllByDefault = $showAllByDefault;
        $this->selectedByDefault = $selectedByDefault;
        $this->required = $required;
        $this->allOption = $allOption;
    }

    public function render()
    {
        if( ! is_null($this->selectedByDefault) ){
            $entitieSelectedByDefault = $this->Model::find($this->selectedByDefault) ?? 'all';
        } else {
            $entities = $this->queryEntities();
            if($entities?->count() == 0){
                $this->resetPage("$this->entitieName-page");
            }
        }
        return view('livewire..entities.base.index.choose', [
            'entities' => $entities ?? null,
            'entitieSelectedByDefault' => $entitieSelectedByDefault ?? null,
            'required' => $this->required,
            'allOption' => $this->allOption,
            'name' => $this->entitieName,
            'spanishName' => $this->entitieSpanishName,
            'gender' => $this->entitieGender
        ]);
    }

    protected function queryEntities(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $entities = null;
        } else {
            $entities =
                $this->Model::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: "$this->entitieName-page");
        }
        return $entities;
    }

    protected function validateSearch(): string|null
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 255
            ){
                $validated = mb_strtoupper($this->search);
            }
        }
        return $validated;
    }
}
