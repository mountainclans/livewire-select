<?php

namespace MountainClans\LivewireSelect;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewireSelectServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-select')
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Blade::component('livewire-select::components/select', 'ui.select');
    }
}
