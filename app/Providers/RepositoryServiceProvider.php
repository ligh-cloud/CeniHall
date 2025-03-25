<?php

namespace App\Providers;

use App\Repositories\MovieRepository;
use App\Repositories\MovieRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\SiegeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MovieRepositoryInterface::class , MovieRepository::class);
//        $this->app->bind(\App\Repositories\PaymentRepositoryInterface::class, \App\Repositories\PaymentRepository::class);
//        $this->app->bind(\App\Repositories\ReservationRepositoryInterface::class, \App\Repositories\ReservationRepository::class);
        $this->app->bind(\App\Repositories\SalleRepositoryInterface::class, \App\Repositories\SalleRepository::class);
        $this->app->bind(\App\Repositories\SeanceRepositoryInterface::class, \App\Repositories\SeanceRepository::class);
        $this->app->bind(\App\Repositories\SiegeRepositoryInterface::class, SiegeRepository::class);
//        $this->app->bind(\App\Repositories\TicketRepositoryInterface::class, \App\Repositories\TicketRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
