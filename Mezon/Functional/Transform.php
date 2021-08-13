<?php
namespace Mezon\Functional;

/**
 * Class Transform
 *
 * @package Mezon
 * @subpackage Functional
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/18)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Transformation algorithms
 */
class Transform
{

    /**
     * Method converts one array to another
     *
     * @param array $records
     *            records to be converted
     * @param callable $converter
     *            converter
     */
    public static function convert(array &$records, callable $converter): void
    {
        $result = [];

        foreach ($records as $record) {
            list ($key, $value) = $converter($record);

            $result[$key] = $value;
        }

        $records = $result;
    }

    /**
     * Method converts one array to another
     *
     * @param array $records
     *            records to be converted
     * @param callable $converter
     *            converter
     */
    public static function convertElements(array &$records, callable $converter): void
    {
        foreach ($records as $i => $record) {
            $records[$i] = $converter($record);
        }
    }

    /**
     * Method creates algorithm wich converts array of records to key-value pairs
     *
     * @param string $keyFieldName
     *            name of the key field
     * @param string $valueFieldName
     *            name of the value field
     * @return callable [$key, $value]
     */
    public static function arrayToKeyValue(string $keyFieldName, string $valueFieldName): callable
    {
        return /**
         *
         * @param array|object $record
         *            record to be transformed
         */
        function ($record) use ($keyFieldName, $valueFieldName) {
            return [
                Fetcher::getField($record, $keyFieldName),
                Fetcher::getField($record, $valueFieldName)
            ];
        };
    }

    /**
     * Method filters
     *
     * @param array $records
     *            records to be converted
     * @param callable $filter
     *            filtration fuction
     */
    public static function filter(array &$records, callable $filter): void
    {
        $result = [];

        foreach ($records as $key => $record) {
            if ($filter($record)) {
                $result[$key] = $record;
            }
        }

        $records = $result;
    }
    
    /**
     * Method creates hash ffrom list of records by their field value
     *
     * @param array $data
     *            origin array of records
     * @param string $field
     *            field name
     * @return array hash
     */
    public static function hashByField(array $data, string $field): array
    {
        $fieldValues = Fetcher::getFields($data, $field);
        $fieldValues = array_unique($fieldValues);
        
        $result = [];
        
        foreach ($fieldValues as $fieldValue) {
            $result[$fieldValue] = [];
            
            foreach ($data as $record) {
                if (Fetcher::getField($record, $field) === $fieldValue) {
                    $result[$fieldValue][] = $record;
                }
            }
        }
        
        return $result;
    }
}
