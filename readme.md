UtilsBundle
===================

UtilsBundle is a collection of small simple helper methods in different types: Strings, Date Time etc.
Check files inside **Lib** folder

Install
--------

To use this bundle require it:

```sh
composer require nnmer/utils-bundle
```

Or config via composer.json

``` json
{
    "require": {
        "nnmer/utils-bundle": "dev-master"
    }
}
```


Add to Kernel.php:

```php
    public function registerBundles()
    {
        $bundles = array(
        ...
            new Nnmer\UtilsBundle\NnmerUtilsBundle(),
        ...
```
