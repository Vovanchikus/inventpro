<?php

namespace Samvol\Inventory\Tests;

if (class_exists('System\\Tests\\Bootstrap\\PluginTestCase')) {
    abstract class BaseTestCase extends \System\Tests\Bootstrap\PluginTestCase
    {
    }
} else {
    abstract class BaseTestCase extends \PluginTestCase
    {
    }
}

abstract class InventoryPluginTestCase extends BaseTestCase
{
    protected $refreshPlugins = [
        'Winter.User',
        'Samvol.Inventory',
    ];
}
