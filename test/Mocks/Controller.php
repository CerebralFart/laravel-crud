<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Cerebralfart\LaravelCRUD\CRUDController;
use Illuminate\Database\Eloquent\Builder;

class Controller extends CRUDController {
    public $model = Pokemon::class;
    public $views = 'views';

    public $searchColumns = ['name'];
    public $searchMode = 'LIKE'; // TODO sqlite doesn't seem to support ILIKE

    public function filterSmall(Builder $builder) {
        $builder->where('height', '<', 1);
    }

    public function filterLight(Builder $builder) {
        $builder->where('weight', '<', 10);
    }
}
