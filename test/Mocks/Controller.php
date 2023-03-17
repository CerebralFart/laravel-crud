<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Cerebralfart\LaravelCRUD\CRUDController;
use Illuminate\Database\Eloquent\Builder;

class Controller extends CRUDController {
    public ?string $model = Pokemon::class;
    public ?string $views = 'views';

    public array $searchColumns = ['name'];
    public string $searchMode = 'LIKE'; // TODO sqlite doesn't seem to support ILIKE

    public array $validationRules = [
        'name' => 'required|min:3'
    ];

    public function filterSmall(Builder $builder) {
        $builder->where('height', '<', 1);
    }

    public function filterLight(Builder $builder) {
        $builder->where('weight', '<', 10);
    }
}
