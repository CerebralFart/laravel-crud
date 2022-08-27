<?php

namespace Cerebralfart\LaravelCRUD\Test;

use Cerebralfart\LaravelCRUD\Contracts\FileNamingContract;
use Cerebralfart\LaravelCRUD\Contracts\ValidationContract;
use Cerebralfart\LaravelCRUD\Strategies\AttributeFolderFileNaming;
use Cerebralfart\LaravelCRUD\Strategies\ModelFolderFileNaming;
use Exception;

class HierarchyTest extends TestCase {
    private static $count = 0;

    private static $cases = [
        FileNamingContract::class => [AttributeFolderFileNaming::class, ModelFolderFileNaming::class],
        ValidationContract::class => [],
    ];

    public function test_traits() {
        foreach (self::$cases as $interface => $traits) {
            foreach ($traits as $trait) {
                $name = 'HierarchyTest' . self::$count++;
                try {
                    eval("class $name implements $interface { use $trait; }");
                    $this->pass("Trait [$trait] properly implements [$interface]");
                } catch (Exception $e) {
                    $this->fail("Trait [$trait] does not properly implement [$interface]");
                }
            }
        }
    }
}
