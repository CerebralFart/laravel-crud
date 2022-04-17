<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Policy implements \Cerebralfart\LaravelCRUD\Policy {
    public function viewAny(?Authorizable $user): Response {
        return Response::allow();
    }

    public function view(?Authorizable $user, Model $subject): Response {
        return Response::allow();
    }

    public function create(?Authorizable $user): Response {
        return Response::allow();
    }

    public function update(?Authorizable $user, Model $subject): Response {
        return Response::allow();
    }

    public function delete(?Authorizable $user, Model $subject): Response {
        return Response::allow();
    }
}
