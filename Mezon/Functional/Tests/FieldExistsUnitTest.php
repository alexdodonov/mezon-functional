<?php
namespace Mezon\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Functional\Fetcher;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FieldExistsUnitTest extends TestCase
{
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
        $this->assertTrue(Fetcher::fieldExists($arr, 'f1'));
        $this->assertTrue(Fetcher::fieldExists($arr, 'f2'));
        $this->assertFalse(Fetcher::fieldExists($arr, 'f3'));
        $this->assertTrue(Fetcher::fieldExists($arr2, 'f3'));
        $this->assertFalse(Fetcher::fieldExists($arr2, 'f4'));
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
        $this->assertTrue(Fetcher::fieldExists($arr, 'f2'));
        $this->assertTrue(Fetcher::fieldExists($arr, 'f22'));
        $this->assertFalse(Fetcher::fieldExists($arr, 'f22', false));
    }
}
