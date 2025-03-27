<?php

namespace App\Providers;

use App\Filters\OrderFilter;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
         * Биндим все фильтры для иньекции в классах
         * за исключением абстрактных классов, трейтов и интерфейсов в папке
         */
        foreach (glob(app_path('Filters') . '/*Filter.php') as $filename) {
            $namespace = 'App\\Filters\\' . basename($filename, '.php');
            $reflection = new \ReflectionClass($namespace);

            if($reflection->isAbstract()) continue;
            if($reflection->isInterface()) continue;
            if($reflection->isTrait()) continue;

            $this->app->bind(
                abstract: $namespace,
                concrete: fn(Application $app) => new $namespace(request()->query->all())
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
