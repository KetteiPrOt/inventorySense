<?php

namespace App\Livewire\Entities\Users\Index;

use App\Livewire\Entities\Base\Index\Choose as BaseChoose;
use App\Models\User;

class Choose extends BaseChoose
{
    /**
     * The Entitie Model class name.
     */
    protected ?string $Model = User::class;

    /**
     * The Entitie name.
     */
    protected ?string $entitieName = 'user';

    /**
     * The Entitie spanish name.
     */
    protected ?string $entitieSpanishName = 'usuario';

    /**
     * The Entitie spanish gender. Can be 'male' or 'female'.
     */
    protected ?string $entitieGender = 'male';
}
