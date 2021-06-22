<?php
namespace Mezon\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Functional\Transform;

/** @psalm-suppress PropertyNotSetInConstructor */
class TransformUnitTest extends TestCase
{

    /**
     * Testing converter
     */
    public function testHashingTransformer(): void
    {
        // setup
        $data = [
            [
                'id' => 1,
                'title' => '11'
            ],
            [
                'id' => 2,
                'title' => '22'
            ],
        ];

        // test body
        Transform::convert($data, Transform::arrayToKeyValue('id', 'title'));

        // assertions
        $this->assertTrue(isset($data[1]));
        $this->assertTrue(isset($data[2]));

        $this->assertEquals('11', $data[1]);
        $this->assertEquals('22', $data[2]);
    }
}
