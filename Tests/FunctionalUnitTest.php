<?php

/**
 * Transformation function multiplies 'foo' field
 */
function transform2x($object)
{
    $object->foo *= 2;

    return $object;
}

class FunctionalUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing getting field function
     */
    public function testGetFieldArray(): void
    {
        // setup
        $arr = [
            'foo' => 'bar'
        ];

        // test body
        $result = \Mezon\Functional\Functional::getField($arr, 'foo');

        // assertions
        $this->assertEquals($result, 'bar', 'Invalid value');
    }

    /**
     * Testing getting field function
     */
    public function testGetField2Array(): void
    {
        // setup
        $arr = [
            'foo' => 'bar',
            'foo2' => 'bar2'
        ];

        // test body
        $result = \Mezon\Functional\Functional::getField($arr, 'foo2');

        // assertions
        $this->assertEquals($result, 'bar2', 'Invalid value');
    }

    /**
     * Testing getting field function
     */
    public function testGetFieldObject(): void
    {
        // setup
        $obj = new stdClass();
        $obj->foo = 'bar';

        // test body
        $result = \Mezon\Functional\Functional::getField($obj, 'foo');

        // assertions
        $this->assertEquals($result, 'bar', 'Invalid value');
    }

    /**
     * Testing getting field function
     */
    public function testGetField2Object(): void
    {
        // setup
        $obj = new stdClass();
        $obj->foo = 'bar';
        $obj->foo2 = 'bar2';

        // test body
        $result = \Mezon\Functional\Functional::getField($obj, 'foo2');

        // assertions
        $this->assertEquals($result, 'bar2', 'Invalid value');
    }

    /**
     * Testing fields fetching
     */
    public function testFieldsFetching(): void
    {
        // setup
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->foo = 2;

        $obj3 = new stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body
        $result = \Mezon\Functional\Functional::getFields($data, 'foo');

        // assertions
        $this->assertEquals(count($result), 3, 'Invalid count');

        $this->assertEquals($result[0], 1, 'Invalid value');
        $this->assertEquals($result[1], 2, 'Invalid value');
        $this->assertEquals($result[2], 3, 'Invalid value');
    }

    /**
     * Testing fields setting
     */
    public function testFieldsSetting(): void
    {
        // setup
        $values = [
            1,
            2,
            3
        ];
        $obj1 = new stdClass();
        $obj2 = new stdClass();

        $data = [
            $obj1,
            $obj2
        ];

        // test body
        \Mezon\Functional\Functional::setFieldsInObjects($data, 'foo', $values);

        // assertions
        $this->assertEquals(count($data), 3, 'Invalid count');

        $this->assertEquals($data[0]->foo, 1, 'Invalid value');
        $this->assertEquals($data[1]->foo, 2, 'Invalid value');
        $this->assertEquals($data[2]->foo, 3, 'Invalid value');
    }

    /**
     * Testing fields summation
     */
    public function testFieldsSum(): void
    {
        // setup
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->foo = 2;

        $obj3 = new stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body and assertions
        $this->assertEquals(\Mezon\Functional\Functional::sumFields($data, 'foo'), 6, 'Invalid sum');
    }

    /**
     * Method will test transformation function
     */
    public function testTransform(): void
    {
        // setup
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->foo = 2;

        $obj3 = new stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body
        \Mezon\Functional\Functional::transform($data, 'transform2x');

        // assertions
        $this->assertEquals($data[0]->foo, 2, 'Invalid value');
        $this->assertEquals($data[1]->foo, 4, 'Invalid value');
        $this->assertEquals($data[2]->foo, 6, 'Invalid value');
    }

    /**
     * Testing recursive fields summation
     */
    public function testRecursiveSum(): void
    {
        // setup
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->foo = 2;

        $obj3 = new stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            [
                $obj2,
                $obj3
            ]
        ];

        // test body and assertions
        $this->assertEquals(\Mezon\Functional\Functional::sumFields($data, 'foo'), 6, 'Invalid sum');
    }

    /**
     * Data provider for the test testFilter
     *
     * @return array test data
     */
    public function filterDataProvider(): array
    {
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->foo = 2;

        $obj3 = new stdClass();
        $obj3->foo = 1;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        return [
            [
                $data,
                '==',
                1,
                2
            ],
            [
                $data,
                '>',
                1,
                1
            ],
            [
                $data,
                '<',
                2,
                2
            ],
            [
                $data,
                '!=',
                1,
                1
            ],
        ];
    }

    /**
     * This method is testing filtration function
     *
     * @dataProvider filterDataProvider
     */
    public function testFilter(array $data, string $operation, int $value, int $result): void
    {
        // setup, test body and assertions
        $this->assertEquals(
            $result,
            count(\Mezon\Functional\Functional::filter($data, 'foo', $operation, $value)),
            'Invalid filtration');
    }

    /**
     * This method is testing filtration function in a recursive mode
     */
    public function testGetFieldRecursive(): void
    {
        // setup
        $obj1 = new stdClass();
        $obj1->foo = 1;

        $obj2 = new stdClass();
        $obj2->bar = 2;
        $obj1->obj2 = $obj2;

        $obj3 = new stdClass();
        $obj3->eak = 3;
        $obj1->obj3 = $obj3;

        // test body and assertions
        $this->assertEquals(\Mezon\Functional\Functional::getField($obj1, 'eak'), 3, 'Invalid getting');
    }

    /**
     * Testing fields replacing in arrays
     */
    public function testReplaceFieldInArrays(): void
    {
        // setup
        $records = [
            [
                'from' => 0
            ],
            [
                'from' => 1
            ]
        ];

        // test body
        \Mezon\Functional\Functional::replaceField($records, 'from', 'to');

        // assertions
        $this->assertTrue(isset($records[0]['to']), 'Field was not replaced');
        $this->assertTrue(isset($records[1]['to']), 'Field was not replaced');

        $this->assertEquals($records[0]['to'], 0, 'Field was not replaced correctly');
        $this->assertEquals($records[1]['to'], 1, 'Field was not replaced correctly');
    }

    /**
     * Testing fields replacing in objects
     */
    public function testReplaceFieldInObjects(): void
    {
        // setup
        $object0 = new stdClass();
        $object0->from = 0;

        $object1 = new stdClass();
        $object1->from = 1;

        $records = [
            $object0,
            $object1
        ];

        // test body
        \Mezon\Functional\Functional::replaceField($records, 'from', 'to');

        // assertions
        $this->assertTrue(isset($records[0]->to), 'Field was not replaced');
        $this->assertTrue(isset($records[1]->to), 'Field was not replaced');

        $this->assertEquals(0, $records[0]->to, 'Field was not replaced correctly');
        $this->assertEquals(1, $records[1]->to, 'Field was not replaced correctly');
    }

    /**
     * Testing 'replaceFields' method
     */
    public function testReplaceFields(): void
    {
        // setup
        $objects = [
            [
                'id' => 1,
                'field' => 'f'
            ]
        ];

        // test body
        \Mezon\Functional\Functional::replaceFields($objects, [
            'id',
            'field'
        ], [
            '_id',
            '_field'
        ]);

        // assertions
        $this->assertEquals(1, $objects[0]['_id']);
        $this->assertEquals('f', $objects[0]['_field']);
    }

    /**
     * Testing 'replaceFieldInEntity' method
     */
    public function testReplaceFieldInEntity(): void
    {
        // setup
        $object = [
            'id' => 1
        ];

        // test body
        \Mezon\Functional\Functional::replaceFieldInEntity($object, 'id', 'id2');

        // assertions
        $this->assertArrayHasKey('id2', $object);
        $this->assertEquals(1, $object['id2']);
    }

    /**
     * Testing 'replaceFieldsInEntity' method
     */
    public function testReplaceFieldsInEntity(): void
    {
        // setup
        $object = [
            'id' => 1,
            'field' => 'f'
        ];

        // test body
        \Mezon\Functional\Functional::replaceFieldsInEntity($object, [
            'id',
            'field'
        ], [
            'id2',
            'field2'
        ]);

        // assertions
        $this->assertArrayHasKey('id2', $object);
        $this->assertArrayHasKey('field2', $object);
        $this->assertEquals(1, $object['id2']);
        $this->assertEquals('f', $object['field2']);
    }

    /**
     * Testing children setting
     */
    public function testSetChildren(): void
    {
        // setup
        $objects = [
            [
                'id' => 1
            ],
            [
                'id' => 3
            ]
        ];
        $records = [
            [
                'f' => 1
            ],
            [
                'f' => 1
            ],
            [
                'f' => 2
            ]
        ];

        // test body
        \Mezon\Functional\Functional::setChildren('children', $objects, 'id', $records, 'f');

        // assertions
        $this->assertTrue(isset($objects[0]['children']), 'Field was not created correctly');
        $this->assertTrue(isset($objects[1]['children']), 'Field was not created correctly');

        $this->assertEquals(2, count($objects[0]['children']), 'Records were not joined');
        $this->assertEquals(0, count($objects[1]['children']), 'Records were not joined');
    }

    /**
     * Method tests records expansion
     */
    public function testExpandingRecords(): void
    {
        // setup
        $arr1 = [
            [
                'id' => 1
            ],
            [
                'id' => 2
            ]
        ];

        $arr2 = [
            [
                'id' => 1,
                'f' => 11
            ],
            [
                'id' => 3,
                'f' => 22
            ]
        ];

        // test body
        \Mezon\Functional\Functional::expandRecordsWith($arr1, 'id', $arr2, 'id');

        // assertions
        $this->assertTrue(isset($arr1[0]['f']), 'Field was not merged');
        $this->assertEquals(11, $arr1[0]['f'], 'Field was not merged');

        $this->assertFalse(isset($arr1[1]['f']), 'Field was merged, but it must not');
    }

    /**
     * Testing records sorting
     */
    function testRecordsSorting(): void
    {
        // setup
        $arr = [
            [
                'i' => 2
            ],
            [
                'i' => 1
            ],
            [
                'i' => 3
            ]
        ];

        // test body
        \Mezon\Functional\Functional::sortRecords($arr, 'i');

        // assertions
        $this->assertEquals(1, $arr[0]['i']);
        $this->assertEquals(2, $arr[1]['i']);
        $this->assertEquals(3, $arr[2]['i']);
    }

    /**
     * Testing records sorting
     */
    function testRecordsSortingReverse(): void
    {
        // setup
        $arr = [
            [
                'i' => 1
            ],
            [
                'i' => 1
            ],
            [
                'i' => 3
            ],
            [
                'i' => 2
            ]
        ];

        // test body
        \Mezon\Functional\Functional::sortRecords($arr, 'i', \Mezon\Functional\Functional::SORT_DIRECTION_DESC);

        // assertions
        $this->assertEquals(3, $arr[0]['i']);
        $this->assertEquals(2, $arr[1]['i']);
        $this->assertEquals(1, $arr[2]['i']);
        $this->assertEquals(1, $arr[3]['i']);
    }

    /**
     * Method tests nested record's addition
     */
    function testSetChild(): void
    {
        // setup
        $objects = [
            [
                'id' => 1
            ],
            [
                'id' => 3
            ]
        ];
        $records = [
            [
                'f' => 1
            ],
            [
                'f' => 3
            ],
            [
                'f' => 2
            ]
        ];

        // test body
        \Mezon\Functional\Functional::setChild('nested', $objects, 'id', $records, 'f');

        // assertions
        $this->assertEquals(1, $objects[0]['nested']['f'], 'Record was not nested');
        $this->assertEquals(3, $objects[1]['nested']['f'], 'Record was not nested');
    }

    /**
     * Method checks does the field exists
     */
    function testFieldExistsPlain(): void
    {
        // setup
        $arr = [
            'f1' => 1,
            'f2' => 2
        ];
        $arr2 = new stdClass();
        $arr2->f3 = 3;

        // test body and assertions
        $this->assertTrue(\Mezon\Functional\Functional::fieldExists($arr, 'f1'));
        $this->assertTrue(\Mezon\Functional\Functional::fieldExists($arr, 'f2'));
        $this->assertFalse(\Mezon\Functional\Functional::fieldExists($arr, 'f3'));
        $this->assertTrue(\Mezon\Functional\Functional::fieldExists($arr2, 'f3'));
        $this->assertFalse(\Mezon\Functional\Functional::fieldExists($arr2, 'f4'));
    }

    /**
     * Method checks does the field recursive.
     */
    function testFieldExistsRecursive(): void
    {
        // setup
        $arr = [
            'f1' => 1,
            'f2' => [
                'f21' => 21,
                'f22' => 22
            ],
            'f3' => 3
        ];

        // test body and assertions
        $this->assertTrue(\Mezon\Functional\Functional::fieldExists($arr, 'f2'));
        $this->assertTrue(\Mezon\Functional\Functional::fieldExists($arr, 'f22'));
        $this->assertFalse(\Mezon\Functional\Functional::fieldExists($arr, 'f22', false));
    }
}
