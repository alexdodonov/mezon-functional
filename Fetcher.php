<?php
namespace Mezon\Functional;

/**
 * Class Compare
 *
 * @package Mezon
 * @subpackage Functional
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/20)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Fetching algorithms
 */
class Fetcher
{

    /**
     * Method returns field of the object/array without recursive inspection
     *
     * @param mixed $record
     *            Record to be analyzed
     * @param string $field
     *            Field name
     * @return bool Does the field $field exists or not
     */
    public static function fieldExistsPlain(&$record, string $field): bool
    {
        if (is_object($record) && isset($record->$field)) {
            return true;
        } elseif (is_array($record) && isset($record[$field])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method returns field of the object/array
     *
     * @param mixed $record
     *            Record to be analyzed
     * @param string $field
     *            Field name
     * @param bool $recursive
     *            Do we need recursive descending
     * @return bool Does the field $field exists or not
     */
    public static function fieldExists(&$record, string $field, bool $recursive = true): bool
    {
        if ($recursive) {
            foreach ($record as $v) {
                if (is_array($v) || is_object($v)) {
                    $result = self::fieldExists($v, $field);

                    if ($result === true) {
                        return $result;
                    }
                }
            }
        }

        return self::fieldExistsPlain($record, $field);
    }

    /**
     * Method fetches all fields from objects/arrays of an array
     *
     * @param mixed $data
     *            Processing record
     * @param string $field
     *            Field name
     * @param bool $recursive
     *            Shold we search the field $field along the whole object
     */
    public static function getFields($data, string $field, $recursive = true)
    {
        $return = array();

        foreach ($data as $record) {
            $return[] = self::getField($record, $field, $recursive);
        }

        return $return;
    }

    /**
     * Method returns field of the object/array
     *
     * @param mixed $record
     *            Processing record
     * @param string $field
     *            Field name
     * @param bool $recursive
     *            Shold we search the field $field along the whole object
     * @return mixed Field value
     */
    public static function getField($record, string $field, bool $recursive = true)
    {
        if ($recursive) {
            foreach ($record as $v) {
                if (is_array($v) || is_object($v)) {
                    $result = self::getField($v, $field);

                    if ($result !== null) {
                        return $result;
                    }
                }
            }
        }

        return self::getFieldPlain($record, $field);
    }

    /**
     * Method returns field of the object/array without recursinve inspection
     *
     * @param mixed $record
     *            Processing record
     * @param string $field
     *            Field name
     * @return mixed Field value
     */
    public static function getFieldPlain($record, string $field)
    {
        if (is_array($record) && isset($record[$field])) {
            return $record[$field];
        } elseif (is_object($record) && isset($record->$field)) {
            return $record->$field;
        } else {
            return null;
        }
    }
}