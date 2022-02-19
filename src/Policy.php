<?php

namespace Cerebralfart\LaravelCRUD;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

interface Policy {
    /* Should the user be able to view all items governed by this policy? */
    public function viewAny(?Authorizable $user): Response;

    /* Should the user be able to view this specific instance of the model? */
    public function view(?Authorizable $user, Model $subject): Response;

    /* Can the user create new instances of this model? */
    public function create(?Authorizable $user): Response;

    /* Can the user update an existing instance of this model? */
    public function update(?Authorizable $user, Model $subject): Response;

    /* Can the user remove an instance of this model? */
    public function delete(?Authorizable $user, Model $subject): Response;
}
