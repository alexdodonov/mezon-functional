<?php
namespace Mezon\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Functional\Transform;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class HashUnitTest extends TestCase
{

    /**
     * Testing method hashByField
     */
    public function testHashCreation(): void
    {
        // setup
        $obj1 = new \stdClass();
        $obj1->field = 1;

        $obj2 = new \stdClass();
        $obj2->field = 1;

        $obj3 = new \stdClass();
        $obj3->field = 2;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body
        $hash = Transform::hashByField($data, 'field');

        // assertions
        $this->assertCount(2, $hash);
        $this->assertCount(2, $hash[1]);
        $this->assertCount(1, $hash[2]);
    }
}
