<?php

namespace App\Livewire\Entities\Products\Index;

use App\Models\Products\Product;
use App\Livewire\Entities\Base\Index\Choose as BaseChoose;
use Livewire\Attributes\Locked;

class Choose extends BaseChoose
{
    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = Product::class;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = 'product';

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = 'producto';

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = 'male';

    /**
     * Retrieves only the products with the inventory started.
     */
    #[Locked]
    public bool $onlyWithStartedInventory = true;

    public function mount(
        bool $showAllByDefault = true,
        null|int|string $selectedByDefault = null,
        bool $required = true,
        bool $allOption = false,
        null|string $inputLabel = null,
        null|string $inputName = null,
        bool $onlyWithStartedInventory = true
    )
    {
        $this->showAllByDefault = $showAllByDefault;
        $this->selectedByDefault = $selectedByDefault;
        $this->required = $required;
        $this->allOption = $allOption;
        $this->onlyWithStartedInventory = $onlyWithStartedInventory;
    }

    public function render()
    {
        if( ! is_null($this->selectedByDefault) ){
            $entitieSelectedByDefault = $this->Model::find($this->selectedByDefault) ?? 'all';
            if(is_object($entitieSelectedByDefault)) $entitieSelectedByDefault?->loadTag();
        } else {
            $entities = $this->queryEntities();
            if($entities?->count() == 0){
                $this->resetPage("$this->entitieName-page");
            }
        }
        return view('livewire.entities.products.index.choose', [
            'entities' => $entities ?? null,
            'entitieSelectedByDefault' => $entitieSelectedByDefault ?? null,
            'required' => $this->required,
            'allOption' => $this->allOption,
            'name' => $this->entitieName,
            'spanishName' => $this->entitieSpanishName,
            'gender' => $this->entitieGender,
            'onlyWithStartedInventory' => $this->onlyWithStartedInventory,
        ]);
    }

    protected function queryEntities(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $entities = null;
        } else {
            $search = mb_strtoupper($validated);
            $entities =
                $this->Model::joinTag($search)
                    ->orderBy('tag')
                    ->simplePaginate(5, pageName: "$this->entitieName-page")->withQueryString();
        }
        return $entities;
    }
}
