<?php

namespace App\Livewire\Entities\Providers\Index;

use App\Models\Provider;
use App\Livewire\Entities\Base\Index\Choose as BaseChoose;

class Choose extends BaseChoose
{
    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = Provider::class;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = 'provider';

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = 'proveedor';

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = 'male';
}
