<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\AuthorRepository;
use App\Models\Book;
use App\Models\Author;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        $this->app->bind(BookRepository::class, function ($app) {
            return new BookRepository($app->make(Book::class));
        });

        $this->app->bind(AuthorRepository::class, function ($app) {
            return new AuthorRepository($app->make(Author::class));
        });
    }

    public function boot()
    {
        //
    }
}
