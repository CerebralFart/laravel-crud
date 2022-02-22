<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Str;

trait PropHelper {
    private static array $requiredProps = ['model', 'views'];

    /**
     * Resolves default values for a given property, or throws an error if it should've been configured
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function __get(string $key): mixed {
        if (in_array($key, static::$requiredProps)) {
            throw new Exception(sprintf("The \$%s property hasn't been defined for %s", $key, Str::afterLast(get_class($this), '\\')));
        } else {
            $value = null; // TODO resolve default values, either from $this->default{$key} or static::$default{$key}
            $this->{$key} = $value;
            return $value;
        }
    }
}
