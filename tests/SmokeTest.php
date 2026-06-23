<?php

use Livewire\Livewire;
use MountainClans\LivewireSelect\LivewireSelectServiceProvider;
use MountainClans\LivewireSelect\Tests\Fixtures\SelectSmokeHost;

it('boots the service provider', function () {
    expect(app()->getLoadedProviders())
        ->toHaveKey(LivewireSelectServiceProvider::class);
});

it('registers the x-ui.select blade component alias', function () {
    $aliases = app('blade.compiler')->getClassComponentAliases();

    expect($aliases)->toHaveKey('ui.select');
});

it('renders the x-ui.select component inside a livewire host', function () {
    Livewire::test(SelectSmokeHost::class)
        ->assertSee('Country')
        ->assertSeeHtml('selectComponent(');
});
