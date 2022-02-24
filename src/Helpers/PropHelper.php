<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;

trait PropHelper {
    private static array $requiredProps = ['model', 'views'];

    private ?ReflectionClass $reflection = null;

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

            $this->initRef();
            $pRef = $this->reflection->hasProperty($defaultName) ? $this->reflection->getProperty($defaultName) : null;
            $mRef = $this->reflection->hasMethod($defaultName) ? $this->reflection->getMethod($defaultName) : null;

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

    private function initRef() {
        if ($this->reflection === null) {
            $this->reflection = new ReflectionClass($this);
        }
    }
}
