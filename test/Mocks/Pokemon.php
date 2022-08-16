<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property string $name
 * @property float $weight
 * @property float $height
 * @property string|null $image;
 */
class Pokemon extends Model {
    public $timestamps = false;
    protected $attributes = ['id' => 0]; // TODO why is this weird SQLite fix neccessary?
    protected $fillable = ['name', 'weight', 'height', 'image'];
}
