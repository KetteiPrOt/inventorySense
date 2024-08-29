<?php

namespace App\Livewire\Entities\Warehouses\Index;

use App\Livewire\Entities\Base\Index\Choose as BaseChoose;

use App\Models\Warehouse;

class Choose extends BaseChoose
{
    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = Warehouse::class;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = 'warehouse';

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = 'bodega';

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = 'female';
}
