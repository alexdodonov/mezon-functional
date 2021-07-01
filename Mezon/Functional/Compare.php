<?php
namespace Mezon\Functional;

/**
 * Class Compare
 *
 * @package Mezon
 * @subpackage Functional
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/18)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Comparison algorithms
 */
class Compare
{

    /**
     * Method creates equal comparator
     *
     * @param string $field
     *            field name
     * @param mixed $value
     *            $value to be compared with
     * @return callable
     */
    public static function equal(string $field, $value): callable
    {
        return /**
         *
         * @param array|object $element
         *            record to be transformed processed
         */
        function ($element) use ($field, $value): bool {
            return Fetcher::getField($element, $field) == $value;
        };
    }

    /**
     * Method creates greater comparator
     *
     * @param string $field
     *            field name
     * @param mixed $value
     *            $value to be compared with
     * @return callable
     */
    public static function greater(string $field, $value): callable
    {
        return /**
         *
         * @param array|object $element
         *            record to be transformed processed
         */
        function ($element) use ($field, $value): bool {
            return Fetcher::getField($element, $field) > $value;
        };
    }

    /**
     * Method creates less comparator
     *
     * @param string $field
     *            field name
     * @param mixed $value
     *            $value to be compared with
     * @return callable
     */
    public static function less(string $field, $value): callable
    {
        return /**
         *
         * @param array|object $element
         *            record to be transformed processed
         */
        function ($element) use ($field, $value): bool {
            return Fetcher::getField($element, $field) < $value;
        };
    }

    /**
     * Method creates not equal comparator
     *
     * @param string $field
     *            field name
     * @param mixed $value
     *            $value to be compared with
     * @return callable
     */
    public static function notEqual(string $field, $value): callable
    {
        return /**
         *
         * @param array|object $element
         *            record to be transformed processed
         */
        function ($element) use ($field, $value): bool {
            return Fetcher::getField($element, $field) != $value;
        };
    }
}