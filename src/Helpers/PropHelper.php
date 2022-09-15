<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;

trait PropHelper {
    private static array $requiredProps = ['model', 'views'];

    public function bootPropHelper() {
        foreach (self::$requiredProps as $prop) {
            if ($this->$prop === null) {
                throw new Exception(sprintf("The \$%s property hasn't been defined for %s", $prop, Str::afterLast(get_class($this), '\\')));
            }
        }
    }
}
