<?php

namespace MountainClans\LivewireSelect\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ViewErrorBag;
use Livewire\LivewireServiceProvider;
use MountainClans\LivewireSelect\LivewireSelectServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MountainClans\\LivewireSelect\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Вне HTTP-запроса middleware ShareErrorsFromSession не отрабатывает,
        // а компонент использует @error — расшариваем пустой bag вручную.
        $this->app['view']->share('errors', new ViewErrorBag);
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireSelectServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
