<?php
namespace Mezon\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Functional\Fetcher;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetFieldPlainUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function getFieldPlainDataProvider(): array
    {
        $obj1 = new \stdClass();
        $obj1->field = 'object value';

        $obj2 = new \stdClass();
        $obj2->unexisting = 'object value';

        return [
            // #0, array case, field exists
            [
                [
                    'field' => 'array value'
                ],
                'array value'
            ],
            // #1, array case, field does not exists
            [
                [
                    'unexisting' => ''
                ],
                null
            ],
            // #2, object case, field exists
            [
                $obj1,
                'object value'
            ],
            // #3, object case, field not exists
            [
                $obj2,
                null
            ],
            // #4, invalid data
            [
                'string',
                null
            ]
        ];
    }

    /**
     * Testing method get field plain
     *
     * @param array|object $record
     *            record
     * @param string|null $expected
     *            expected value
     * @dataProvider getFieldPlainDataProvider
     */
    public function testGetFieldPlain($record, $expected): void
    {
        // test body
        /** @var string|null $result */
        $result = Fetcher::getFieldPlain($record, 'field');

        // assertions
        $this->assertEquals($expected, $result);
    }
}
