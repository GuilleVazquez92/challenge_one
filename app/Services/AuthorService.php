<?php

namespace App\Services;

use App\Repositories\Eloquent\AuthorRepository;

class AuthorService extends BaseService
{
    public function __construct(AuthorRepository $repository)
    {
        parent::__construct($repository);
    }
}
