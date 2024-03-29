<?php

namespace App\View\Components\Entities\Permissions\Edit;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public object $permissions;

    public object $translator;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?object $permissions = null, object $translator
    )
    {
        if(is_null($permissions)){
            $this->permissions = collect([]);
        } else {
            $this->permissions = $permissions;
        }
        $this->translator = $translator;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components..entities.permissions.edit.input');
    }
}
