<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;

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
            $defaultName = 'default' . Str::ucfirst($key);

            $cRef = new ReflectionClass($this); // TODO this should be cached
            $pRef = $cRef->hasProperty($defaultName) ? $cRef->getProperty($defaultName) : null;
            $mRef = $cRef->hasMethod($defaultName) ? $cRef->getMethod($defaultName) : null;

            $value = match (true) {
                $pRef !== null && !$pRef->isStatic() => $this->{$defaultName},
                $mRef !== null && !$mRef->isStatic() => $this->{$defaultName}(),
                $pRef !== null && $pRef->isStatic() => $this::$$defaultName, // I hate the inconsistency between this line and the next :(
                $mRef !== null && $mRef->isStatic() => $this::$defaultName(),
                default => null,
            };

            // Set the property to speed up future access
            $this->{$key} = $value;
            return $value;
        }
    }
}
