<?php
namespace Mezon\Functional;

/**
 * Class Functional
 *
 * @package Mezon
 * @subpackage Functional
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Functional algorithms
 */
class Functional
{

    /**
     * Method returns field of the object/array without recursinve inspection
     *
     * @param mixed $record
     *            Processing record
     * @param string $field
     *            Field name
     * @return mixed Field value
     * @deprecated Use Fetcher::getFieldPlain instead. Deprecated since 2020-01-21
     */
    public static function getFieldPlain($record, string $field)
    {
        return Fetcher::getFieldPlain($record, $field);
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
     * @deprecated Use Fetcher::getField instead. Deprecated since 2020-01-21
     */
    public static function getField($record, string $field, bool $recursive = true)
    {
        return Fetcher::getField($record, $field, $recursive);
    }

    /**
     * Method sets existing field of the object/array
     *
     * @param mixed $record
     *            Processing record
     * @param string $field
     *            Field name
     * @param mixed $value
     *            Value to be set
     */
    protected static function setExistingField(&$record, string $field, $value): void
    {
        foreach ($record as $i => $v) {
            if ($i == $field) {
                if (is_object($record)) {
                    $record->$field = $value;
                } else {
                    $record[$field] = $value;
                }
            } elseif (is_array($v) || is_object($v)) {
                if (is_array($record)) {
                    self::setExistingField($record[$i], $field, $value);
                } else {
                    self::setExistingField($record->$i, $field, $value);
                }
            }
        }
    }

    /**
     * Method sets field of the object/array
     *
     * @param mixed $record
     *            Processing record
     * @param string $field
     *            Field name
     * @param mixed $value
     *            Value to be set
     */
    public static function setField(&$record, string $field, $value): void
    {
        $existing = Fetcher::getField($record, $field);

        if ($existing === null) {
            // add field if it does not exist
            if (is_object($record)) {
                $record->$field = $value;
            } else {
                $record[$field] = $value;
            }
        } else {
            // set existing field
            self::setExistingField($record, $field, $value);
        }
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
     * @return mixed value of the field
     * @deprecated Use Fetcher::getFields instead. Deprecated since 2020-01-21
     */
    public static function getFields($data, string $field, $recursive = true)
    {
        return Fetcher::getFields($data, $field, $recursive);
    }

    /**
     * Method sets fields $fieldName in array of objects $objects with $values
     *
     * @param array $objects
     *            Array of objects to be processed
     * @param string $fieldName
     *            Field name
     * @param array $values
     *            Values to be set
     */
    public static function setFieldsInObjects(&$objects, string $fieldName, array $values): void
    {
        foreach ($values as $i => $value) {
            if (isset($objects[$i]) === false) {
                $objects[$i] = new \stdClass();
            }

            $objects[$i]->$fieldName = $value;
        }
    }

    /**
     * Method sums fields in an array of objects.
     *
     * @param array $objects
     *            Array of objects to be processed
     * @param string $fieldName
     *            Field name
     * @return mixed Sum of fields.
     */
    public static function sumFields(&$objects, $fieldName)
    {
        $sum = 0;

        foreach ($objects as $object) {
            if (is_array($object) && isset($object[$fieldName])) {
                $sum += $object[$fieldName];
            } elseif (is_object($object) && isset($object->$fieldName)) {
                $sum += $object->$fieldName;
            } else {
                $sum += self::sumFields($object, $fieldName);
            }
        }

        return $sum;
    }

    /**
     * Method transforms objects in array
     *
     * @param array $objects
     *            Array of objects to be processed
     * @param callable $transformer
     *            Transform function
     * @deprecated Use Transform::convertElements instead. Deprecated since 2020-01-21
     */
    public static function transform(array &$objects, callable $transformer): void
    {
        Transform::convertElements($objects, $transformer);
    }

    /**
     * Method filters objects in array
     *
     * @param array $objects
     *            List of records to be filtered
     * @param string $field
     *            Filter field
     * @param string $operation
     *            Filtration operation
     * @param mixed $value
     *            Filtration value
     * @return array List of filtered records
     * @deprecated Use \Functional\Transform::filter The method was marked as deprecated 2020/01/20
     */
    public static function filter(array &$objects, string $field, string $operation = '==', $value = false): array
    {
        $return = $objects;

        if ($operation == '==') {
            Transform::filter($return, \Mezon\Functional\Compare::equal($field, $value));
        } elseif ($operation == '>') {
            Transform::filter($return, \Mezon\Functional\Compare::greater($field, $value));
        } elseif ($operation == '<') {
            Transform::filter($return, \Mezon\Functional\Compare::less($field, $value));
        } else {
            Transform::filter($return, \Mezon\Functional\Compare::notEqual($field, $value));
        }

        return $return;
    }

    /**
     * Method replaces one field to another in record
     *
     * @param array|object|mixed $object
     *            Object to be processed
     * @param string $fieldFrom
     *            Field name to be replaced
     * @param string $fieldTo
     *            Field name to be added
     */
    public static function replaceFieldInEntity(&$object, string $fieldFrom, string $fieldTo): void
    {
        if (is_array($object)) {
            if (isset($object[$fieldFrom])) {
                $value = $object[$fieldFrom];
                unset($object[$fieldFrom]);
                $object[$fieldTo] = $value;
            }
        } elseif (is_object($object)) {
            if (isset($object->$fieldFrom)) {
                $value = $object->$fieldFrom;
                unset($object->$fieldFrom);
                $object->$fieldTo = $value;
            }
        } else {
            throw (new \Exception('Unknown entyty type for ' . serialize($object)));
        }
    }

    /**
     * Method replaces one field to another in record
     *
     * @param array|object $object
     *            Object to be processed
     * @param array $fieldsFrom
     *            Field names to be replaced
     * @param array $fieldsTo
     *            Field names to be added
     */
    public static function replaceFieldsInEntity(&$object, array $fieldsFrom, array $fieldsTo): void
    {
        foreach ($fieldsFrom as $i => $fieldFrom) {
            self::replaceFieldInEntity($object, $fieldFrom, $fieldsTo[$i]);
        }
    }

    /**
     * Method replaces one field to another in array of records
     *
     * @param array $objects
     *            Objects to be processed
     * @param string $fieldFrom
     *            Field name to be replaced
     * @param string $fieldTo
     *            Field name to be added
     */
    public static function replaceField(array &$objects, string $fieldFrom, string $fieldTo): void
    {
        foreach ($objects as $i => $object) {
            self::replaceFieldInEntity($object, $fieldFrom, $fieldTo);

            $objects[$i] = $object;
        }
    }

    /**
     * Method replaces one field toanother in array of records
     *
     * @param array $objects
     *            Objects to be processed
     * @param array $fieldsFrom
     *            Field names to be replaced
     * @param array $fieldsTo
     *            Field names to be added
     */
    public static function replaceFields(array &$objects, array $fieldsFrom, array $fieldsTo): void
    {
        foreach ($fieldsFrom as $i => $fieldFrom) {
            self::replaceField($objects, $fieldFrom, $fieldsTo[$i]);
        }
    }

    /**
     * Method adds nested records to the original record of objects
     *
     * @param string $field
     *            Field name
     * @param array $objects
     *            The original record of objects
     * @param string $objectField
     *            Filtering field
     * @param array $records
     *            List of nested records
     * @param string $recordField
     *            Filtering field
     * @psalm-suppress DeprecatedMethod
     */
    public static function setChildren(
        string $field,
        array &$objects,
        string $objectField,
        array $records,
        string $recordField): void
    {
        foreach ($objects as &$object) {
            self::setField(
                $object,
                $field,
                self::filter($records, $recordField, '==', Fetcher::getField($object, $objectField, false)));
        }
    }

    /**
     * Method adds nested record to the original record of objects
     *
     * @param string $field
     *            Field name
     * @param array $objects
     *            The original record of objects
     * @param string $objectField
     *            Filtering field
     * @param array $records
     *            List of nested records
     * @param string $recordField
     *            Filtering field
     */
    public static function setChild(
        string $field,
        array &$objects,
        string $objectField,
        array $records,
        string $recordField): void
    {
        foreach ($objects as &$object) {
            foreach ($records as $record) {
                if (Fetcher::getField($object, $objectField, false) == Fetcher::getField($record, $recordField, false)) {
                    self::setField($object, $field, $record);
                }
            }
        }
    }

    /**
     * Method expands record $dest with frields of the $src
     *
     * @param array|object $dest
     *            record to be expanded
     * @param array|object $src
     *            record to be the expander
     */
    public static function expandRecordWith(&$dest, $src): void
    {
        /** @var mixed $srcRecordValue
         @var string $srcRecordField */
        foreach ($src as $srcRecordField => $srcRecordValue) {
            self::setField($dest, $srcRecordField, $srcRecordValue);
        }
    }

    /**
     * Method unites corresponding records
     *
     * @param array $dest
     *            Destination array of records
     * @param string $destField
     *            Field name
     * @param array $src
     *            Source array of records
     * @param string $srcField
     *            Field name
     */
    public static function expandRecordsWith(array &$dest, string $destField, array $src, string $srcField): void
    {
        /** @var array|object $destRecord */
        foreach ($dest as &$destRecord) {
            /** @var array|object $srcRecord */
            foreach ($src as $srcRecord) {
                if (Fetcher::getField($destRecord, $destField, false) == Fetcher::getField($srcRecord, $srcField, false)) {
                    self::expandRecordWith($destRecord, $srcRecord);

                    break;
                }
            }
        }
    }

    /**
     * Sorting directions
     */
    public const SORT_DIRECTION_ASC = 0;

    public const SORT_DIRECTION_DESC = 1;

    /**
     * Method sorts records by the specified field
     *
     * @param mixed[] $objects
     *            records to be sorted
     * @param string $field
     *            field name
     * @param int $direction
     *            direction of sorting
     */
    public static function sortRecords(array &$objects, string $field, int $direction = Functional::SORT_DIRECTION_ASC): void
    {
        /** @var callable(mixed, mixed):int $sort */
        $sort = /**
         *
         * @param array|object $e1
         *            the first record
         * @param array|object $e2
         *            the second record
         * @return int sorting order
         */
        function ($e1, $e2) use ($field, $direction) {
            /** @var mixed $value1 */
            $value1 = Fetcher::getField($e1, $field, false);
            /** @var mixed $value2 */
            $value2 = Fetcher::getField($e2, $field, false);

            $result = 0;

            if ($value1 < $value2) {
                $result = - 1;
            } elseif ($value1 == $value2) {
                $result = 0;
            } else {
                $result = 1;
            }

            return $direction === Functional::SORT_DIRECTION_ASC ? $result : - 1 * $result;
        };

        usort($objects, $sort);
    }

    /**
     * Method returns field of the object/array without recursive inspection
     *
     * @param mixed $record
     *            Record to be analyzed
     * @param string $field
     *            Field name
     * @return bool Does the field $field exists or not
     * @deprecated Should use \Functional\Fetcher::fieldExistsPlain Deprecated in 2020/01/20
     */
    public static function fieldExistsPlain(&$record, string $field): bool
    {
        return Fetcher::fieldExistsPlain($record, $field);
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
     * @deprecated Should use \Functional\Fetcher::fieldExists Deprecated in 2020/01/20
     */
    public static function fieldExists(&$record, string $field, bool $recursive = true): bool
    {
        return Fetcher::fieldExists($record, $field, $recursive);
    }
}
