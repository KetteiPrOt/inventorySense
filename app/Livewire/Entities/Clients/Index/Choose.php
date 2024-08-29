<?php

namespace App\Livewire\Entities\Clients\Index;

use App\Livewire\Entities\Base\Index\Choose as BaseChoose;
use App\Models\Client;

class Choose extends BaseChoose
{
    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = Client::class;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = 'client';

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = 'cliente';

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = 'male';
}
