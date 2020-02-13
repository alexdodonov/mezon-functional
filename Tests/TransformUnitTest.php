<?php

class TransformUnitTest extends \PHPUnit\Framework\TestCase
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
        \Mezon\Functional\Transform::convert($data, \Mezon\Functional\Transform::arrayToKeyValue('id', 'title'));

        // assertions
        $this->assertTrue(isset($data[1]));
        $this->assertTrue(isset($data[2]));

        $this->assertEquals('11', $data[1]);
        $this->assertEquals('22', $data[2]);
    }
}
