<?php

namespace MountainClans\LivewireSelect\Tests\Fixtures;

use Livewire\Component;

class SelectSmokeHost extends Component
{
    public string $country = '';

    public array $values = [
        ['ru', 'Russia'],
        ['us', 'USA'],
    ];

    public function render()
    {
        return <<<'BLADE'
            <div>
                <x-ui.select :values="$values" wire:model="country" label="Country" />
            </div>
            BLADE;
    }
}
