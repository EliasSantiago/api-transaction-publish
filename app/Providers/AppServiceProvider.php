<?php

namespace App\Providers;

use App\Repositories\Eloquent\{UserRepository};
use App\Repositories\Eloquent\{WalletRepository};
use App\Repositories\Eloquent\{TransactionRepository};

use App\Repositories\{UserRepositoryInterface};
use App\Repositories\{WalletRepositoryInterface};
use App\Repositories\{TransactionRepositoryInterface};

use App\Services\TransactionQueueService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        }
        
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->singleton(TransactionRepositoryInterface::class, TransactionRepository::class);

        $this->app->bind(TransactionQueueService::class, function () {
            $host = env('RABBITMQ_HOST');
            $port = env('RABBITMQ_PORT');
            $user = env('RABBITMQ_USER');
            $password = env('RABBITMQ_PASSWORD');

            return new TransactionQueueService($host, $port, $user, $password);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
