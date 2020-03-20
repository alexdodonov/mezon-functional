# Functional programming # Base application class [![Build Status](https://travis-ci.com/alexdodonov/mezon-functional.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-functional) [![codecov](https://codecov.io/gh/alexdodonov/mezon-functional/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-functional)

## Intro

This class provides various members and tools for functional programming. It will help you to work with arrays in a very simple way.

## Modes

Here we can fetch specified field from all objects of array:

```PHP
$obj1 = new stdClass();
$obj1->foo = 1;

$obj2 = new stdClass();
$obj2->foo = 2;

$obj3 = new stdClass();
$obj3->foo = 3;

$Data = array( $obj1 , $obj2 , $obj3 );

// will display array( 1 , 2 ,3 )
var_dump( Functional::getFields( $Data , 'foo' ) );
```

We can also set fields with multyple values:

```PHP
$Values = array( 1 , 2 , 3 );
$obj1 = new stdClass();
$obj2 = new stdClass();

$Data = array( $obj1 , $obj2 );

Functional::setFieldsInObjects( $Data , 'foo' , $Values );
// will display 3 objects
var_dump( $Data );
```

And fianlly we can sum specified fields:

```PHP
$obj1 = new stdClass();
$obj1->foo = 1;

$obj2 = new stdClass();
$obj2->foo = 2;

$obj3 = new stdClass();
$obj3->foo = 3;

$Data = array( $obj1 , $obj2 , $obj3 );

// will display value 6
var_dump( Functional::sumFields( $Data , 'foo' ) );
```

Note that you can recursively walk along the nested arrays:

```PHP
$obj1 = new stdClass();
$obj1->foo = 1;

$obj2 = new stdClass();
$obj2->foo = 2;

$obj3 = new stdClass();
$obj3->foo = 3;

$Data = array( $obj1 , array( $obj2 , $obj3 ) );

// will display value 6
$this->assertEquals( Functional::sumFields( $Data , 'foo' ) , 6 , 'Invalid sum' );
```

We can also transform objects in arrays like this:

```PHP
/**
*   Transformation function multiplies 'foo' field.
*/
function  transform2x( $Object )
{
    $Object->foo *= 2;

    return( $Object );
}
$obj1 = new stdClass();
$obj1->foo = 1;

$obj2 = new stdClass();
$obj2->foo = 2;

$obj3 = new stdClass();
$obj3->foo = 3;

$Data = array( $obj1 , $obj2 , $obj3 );

Functional::transform( $Data , 'transform2x' );
// will display 3 objects
// with 2, 4 and 6 values in their 'foo' fields
var_dump( $Data );
```