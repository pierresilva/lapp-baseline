# Sortable behaviour for Eloquent models

This package provides a trait that adds sortable behaviour to an Eloquent model.

The value of the order column of a new record of a model is determined by the maximum value of the order column of all records of that model + 1.

The package also provides a query scope to fetch all the records in the right order.

## Installation

This package can be installed through Composer.

```
composer require pierresilva/eloquent-sortable
```

## Usage

To add sortable behaviour to your model you must:
1. Implement the `pierresilva\EloquentSortable\Sortable` interface.
2. Use the trait `pierresilva\EloquentSortable\SortableTrait`.
3. Optionally specify which column will be used as the order column. The default is `order_column`.

### Example

```php
use pierresilva\EloquentSortable\Sortable;
use pierresilva\EloquentSortable\SortableTrait;

class MyModel extends Eloquent implements Sortable
{

    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];
    
    ...
}
```

If you don't set a value `$sortable['order_column_name']` the package will assume that your order column name will be named `order_column`.

If you don't set a value `$sortable['sort_when_creating']` the package will automatically assign the highest order number to a new model;

Assuming that the db-table for `MyModel` is empty:

```php
$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 1

$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 2

$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 3

//the trait also provides the ordered query scope
$orderedRecords = MyModel::ordered()->get(); 
```

You can set a new order for all the records using the `setNewOrder`-method

```php
/**
 * the record for model id 3 will have record_column value 1
 * the record for model id 1 will have record_column value 2
 * the record for model id 2 will have record_column value 3
 */
MyModel::setNewOrder([3,1,2]);
```

Optionally you can pass the starting order number as the second argument.

```php
/**
 * the record for model id 3 will have record_column value 11
 * the record for model id 1 will have record_column value 12
 * the record for model id 2 will have record_column value 13
 */
MyModel::setNewOrder([3,1,2], 10);
```

You can also move a model up or down with these methods:

```php 
$myModel->moveOrderDown();
$myModel->moveOrderUp();
```

You can also move a model to the first or last position:

```php 
$myModel->moveToStart();
$myModel->moveToEnd();
```

You can swap the order of two models:

```php 
MyModel::swapOrder($myModel, $anotherModel);
```

## Tests

The package contains some integration/smoke tests, set up with Orchestra. The tests can be run via phpunit.

```
$ vendor/bin/phpunit
```

## Credits

- [Freek Van der Herten](https://murze.be)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

