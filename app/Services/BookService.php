<?php

namespace App\Services;

use App\Repositories\Eloquent\BookRepository;

class BookService extends BaseService
{
    public function __construct(BookRepository $repository)
    {
        parent::__construct($repository);
    }
}

