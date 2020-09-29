# Functional programming
[![Build Status](https://travis-ci.com/alexdodonov/mezon-functional.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-functional) [![codecov](https://codecov.io/gh/alexdodonov/mezon-functional/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-functional)

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
var_dump( \Mezon\Functional\Fetcher::getFields( $Data , 'foo' ) );
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
var_dump(Functional::sumFields( $Data , 'foo' ));
```

And this code will also work:

```php
// will display value 3
var_dump(Functional::sumFields( [
    ['foo'=>1],
    ['foo'=>2]
] , 'foo' ));
```

## Transformations

We can also transform objects in arrays like this (the most basic and simple way):

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

But if you need more complex transformations, you can use class Transform. It will allow you to build entirely new array.

```PHP
$data = [
	1 , 2
];

Transform::convert($data,function($item){return [10*$item, 100*$item];});

var_dump($data);

// will output
// [10=>100 , 20=>200]
```

And if you want to transform only elements of the array, then use Transform::convertElements

```php

$data = [
	1 , 2
];

Transform::convertElements($data,function($item){return 10 * $item;});

var_dump($data);

// will output
// [0=>10 , 1=>20]
```