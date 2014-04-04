GeographicComponent
===================

This component is used to get the range of latitude and longitude from a given point and distance.
It creates a rectangular boundary.

How to use
==========

You can call it in your controller like a normal component

```php
public $components = array(
        'Geographic'
    );
```
You can then create an array to store returning boundary array

```php
/calculate the boundary for the lat, long and limiting distance
$boundary = $this->Geographic->CreateBoundary($latitude, $longitude, 50, "km");
```
You can use it as a condition inside your find query like following (assuming you have a model named Location)
```php
//boundary condition to bound the location
$boundary_condition = array(
    'Location.latitude BETWEEN ? AND ?' => array(
        $boundary["S"]["lat"], $boundary["N"]["lat"]
    ),
    'Location.longitude BETWEEN ? AND ?' => array(
        $boundary["W"]["lon"], $boundary["E"]["lon"]
    )
);
```