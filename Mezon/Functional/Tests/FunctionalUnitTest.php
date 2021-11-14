<?php
namespace Mezon\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Functional\Functional;
use Mezon\Functional\Fetcher;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FunctionalUnitTest extends TestCase
{

    /**
     * Testing getting field function
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetFieldArray(): void
    {
        // setup
        $arr = [
            'foo' => 'bar'
        ];

        // test body
        $result = Functional::getField($arr, 'foo');

        // assertions
        $this->assertEquals($result, 'bar');
    }

    /**
     * Testing getting field function
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetField2Array(): void
    {
        // setup
        $arr = [
            'foo' => 'bar',
            'foo2' => 'bar2'
        ];

        // test body
        $result = Functional::getField($arr, 'foo2');

        // assertions
        $this->assertEquals($result, 'bar2');
    }

    /**
     * Testing getting field function
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetFieldObject(): void
    {
        // setup
        $obj = new \stdClass();
        $obj->foo = 'bar';

        // test body
        $result = Functional::getField($obj, 'foo');

        // assertions
        $this->assertEquals($result, 'bar');
    }

    /**
     * Testing getting field function
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetField2Object(): void
    {
        // setup
        $obj = new \stdClass();
        $obj->foo = 'bar';
        $obj->foo2 = 'bar2';

        // test body
        $result = Functional::getField($obj, 'foo2');

        // assertions
        $this->assertEquals($result, 'bar2');
    }

    /**
     * Testing fields fetching
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testFieldsFetching(): void
    {
        // setup
        $obj1 = new \stdClass();
        $obj1->foo = 1;

        $obj2 = new \stdClass();
        $obj2->foo = 2;

        $obj3 = new \stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body
        $result = Functional::getFields($data, 'foo');

        // assertions
        $this->assertEquals(count($result), 3, 'Invalid count');

        $this->assertEquals($result[0], 1);
        $this->assertEquals($result[1], 2);
        $this->assertEquals($result[2], 3);
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
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();

        $data = [
            $obj1,
            $obj2
        ];

        // test body
        Functional::setFieldsInObjects($data, 'foo', $values);

        // assertions
        $this->assertCount(3, $data, 'Invalid count');

        $this->assertEquals($data[0]->foo, 1);
        $this->assertEquals($data[1]->foo, 2);
        $this->assertEquals($data[2]->foo, 3);
    }

    /**
     * Data provider for the sumFields tests
     *
     * @return array data sets
     */
    public function sumFieldsDataProvider(): array
    {
        $obj1 = new \stdClass();
        $obj1->foo = 1;

        $obj2 = new \stdClass();
        $obj2->foo = 2;

        $obj3 = new \stdClass();
        $obj3->foo = 3;

        $data1 = [
            $obj1,
            $obj2,
            $obj3
        ];

        $data2 = [
            $obj1,
            [
                $obj2,
                $obj3
            ]
        ];

        return [
            [
                $data1,
                6
            ],
            [
                $data2,
                6
            ],
            [
                [
                    [
                        'foo' => 1
                    ],
                    [
                        'foo' => 2
                    ]
                ],
                3
            ]
        ];
    }

    /**
     * Testing fields summation
     *
     * @param array $data
     *            test data
     * @param int $result
     *            expected result
     * @dataProvider sumFieldsDataProvider
     */
    public function testFieldsSum(array $data, int $result): void
    {
        // test body and assertions
        $this->assertEquals(Functional::sumFields($data, 'foo'), $result, 'Invalid sum');
    }

    /**
     * Method will test transformation function
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testTransform(): void
    {
        // setup
        $obj1 = new \stdClass();
        $obj1->foo = 1;

        $obj2 = new \stdClass();
        $obj2->foo = 2;

        $obj3 = new \stdClass();
        $obj3->foo = 3;

        $data = [
            $obj1,
            $obj2,
            $obj3
        ];

        // test body
        Functional::transform($data, function (object $object) {
            $object->foo *= 2;

            return $object;
        });

        // assertions
        $this->assertEquals($data[0]->foo, 2);
        $this->assertEquals($data[1]->foo, 4);
        $this->assertEquals($data[2]->foo, 6);
    }

    /**
     * Data provider for the test testFilter
     *
     * @return array test data
     */
    public function filterDataProvider(): array
    {
        $obj1 = new \stdClass();
        $obj1->foo = 1;

        $obj2 = new \stdClass();
        $obj2->foo = 2;

        $obj3 = new \stdClass();
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
            ]
        ];
    }

    /**
     * This method is testing filtration function
     *
     * @dataProvider filterDataProvider
     * @psalm-suppress DeprecatedMethod
     */
    public function testFilter(array $data, string $operation, int $value, int $result): void
    {
        // setup, test body and assertions
        $this->assertCount($result, Functional::filter($data, 'foo', $operation, $value), 'Invalid filtration');
    }

    /**
     * This method is testing filtration function in a recursive mode
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetFieldRecursive(): void
    {
        // setup
        $obj1 = new \stdClass();
        $obj1->foo = 1;

        $obj2 = new \stdClass();
        $obj2->bar = 2;
        $obj1->obj2 = $obj2;

        $obj3 = new \stdClass();
        $obj3->eak = 3;
        $obj1->obj3 = $obj3;

        // test body and assertions
        $this->assertEquals(Functional::getField($obj1, 'eak'), 3, 'Invalid getting');
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
        Functional::replaceField($records, 'from', 'to');

        // assertions
        $this->assertTrue(isset($records[0]['to']));
        $this->assertTrue(isset($records[1]['to']));

        $this->assertEquals($records[0]['to'], 0);
        $this->assertEquals($records[1]['to'], 1);
    }

    /**
     * Testing fields replacing in objects
     */
    public function testReplaceFieldInObjects(): void
    {
        // setup
        $object0 = new \stdClass();
        $object0->from = 0;

        $object1 = new \stdClass();
        $object1->from = 1;

        $records = [
            $object0,
            $object1
        ];

        // test body
        Functional::replaceField($records, 'from', 'to');

        // assertions
        $this->assertTrue(isset($records[0]->to));
        $this->assertTrue(isset($records[1]->to));

        $this->assertEquals(0, $records[0]->to);
        $this->assertEquals(1, $records[1]->to);
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
        Functional::replaceFields($objects, [
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
        Functional::replaceFieldInEntity($object, 'id', 'id2');

        // assertions
        $this->assertEquals(1, Fetcher::getField($object, 'id2'));
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
        Functional::replaceFieldsInEntity($object, [
            'id',
            'field'
        ], [
            'id2',
            'field2'
        ]);

        // assertions
        $this->assertEquals(1, Fetcher::getField($object, 'id2'));
        $this->assertEquals('f', Fetcher::getField($object, 'field2'));
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
        Functional::setChildren('children', $objects, 'id', $records, 'f');

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
        Functional::expandRecordsWith($arr1, 'id', $arr2, 'id');

        // assertions
        /** @var list<array{f: int}> $arr */
        $this->assertTrue(isset($arr1[0]['f']), 'Field was not merged');
        $this->assertEquals(11, $arr1[0]['f'], 'Field was not merged');

        $this->assertFalse(isset($arr1[1]['f']), 'Field was merged, but it must not');
    }

    /**
     * Testing records sorting
     */
    public function testRecordsSorting(): void
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
        Functional::sortRecords($arr, 'i');

        // assertions
        /** @var list<array{i: int}> $arr */
        $this->assertEquals(1, $arr[0]['i']);
        $this->assertEquals(2, $arr[1]['i']);
        $this->assertEquals(3, $arr[2]['i']);
    }

    /**
     * Testing records sorting
     */
    public function testRecordsSortingReverse(): void
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
        Functional::sortRecords($arr, 'i', Functional::SORT_DIRECTION_DESC);

        // assertions
        /** @var list<array{i: int}> $arr */
        $this->assertEquals(3, $arr[0]['i']);
        $this->assertEquals(2, $arr[1]['i']);
        $this->assertEquals(1, $arr[2]['i']);
        $this->assertEquals(1, $arr[3]['i']);
    }

    /**
     * Method tests nested record's addition
     */
    public function testSetChild(): void
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
        Functional::setChild('nested', $objects, 'id', $records, 'f');

        // assertions
        /** @var array<array-key, array{nested: array}> $objects */
        $this->assertEquals(1, $objects[0]['nested']['f'], 'Record was not nested');
        $this->assertEquals(3, $objects[1]['nested']['f'], 'Record was not nested');
    }

    /**
     * Method checks does the field exists
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testFieldExists(): void
    {
        // setup
        $arr = [
            'f1' => 1,
            'f2' => 2
        ];
        $arr2 = new \stdClass();
        $arr2->f3 = 3;

        // test body and assertions
        $this->assertTrue(Functional::fieldExists($arr, 'f1'));
        $this->assertTrue(Functional::fieldExists($arr, 'f2'));
        $this->assertFalse(Functional::fieldExists($arr, 'f3'));
        $this->assertTrue(Functional::fieldExists($arr2, 'f3'));
        $this->assertFalse(Functional::fieldExists($arr2, 'f4'));
    }

    /**
     * Method checks does the field recursive
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testFieldExistsRecursive(): void
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
        $this->assertTrue(Functional::fieldExists($arr, 'f2'));
        $this->assertTrue(Functional::fieldExists($arr, 'f22'));
        $this->assertFalse(Functional::fieldExists($arr, 'f22', false));
    }

    /**
     * Method checks does the field exists
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testFieldExistsPlain(): void
    {
        // setup
        $arr = [
            'f1' => 1,
            'f2' => 2,
            'rec' => [
                'f4' => 4
            ]
        ];
        $arr2 = new \stdClass();
        $arr2->f3 = 3;

        // test body and assertions
        $this->assertTrue(Functional::fieldExistsPlain($arr, 'f1'));
        $this->assertTrue(Functional::fieldExistsPlain($arr, 'f2'));
        $this->assertFalse(Functional::fieldExistsPlain($arr, 'f3'));
        $this->assertTrue(Functional::fieldExistsPlain($arr2, 'f3'));
        $this->assertFalse(Functional::fieldExistsPlain($arr2, 'f4'));
    }

    /**
     * Testing getFieldPlain
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function testGetFieldPlain(): void
    {
        // setup
        $data = [
            1 => [
                'get-plain-field' => 1
            ],
            'get-plain-field' => 2
        ];

        // test body
        /** @var int */
        $result = Fetcher::getFieldPlain($data, 'get-plain-field');

        // assertions
        $this->assertEquals(2, $result);
    }

    /**
     * Testing setExistingField method
     */
    public function testSetExistingField(): void
    {
        // setup
        $object = new \stdClass();
        $object->rec4 = 0;
        $data = [
            'rec1' => [
                'rec3' => 0
            ],
            'rec2' => $object
        ];

        // test body
        Functional::setField($data, 'rec3', 33);
        Functional::setField($data, 'rec4', 44);

        // assertions
        $this->assertEquals(33, \Mezon\Functional\Fetcher::getField($data, 'rec3'));
        $this->assertEquals(44, \Mezon\Functional\Fetcher::getField($data, 'rec4'));
    }
}
