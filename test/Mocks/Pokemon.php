<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property string $name
 * @property float $weight
 * @property float $height
 */
class Pokemon extends Model {
    public $timestamps = false;
}