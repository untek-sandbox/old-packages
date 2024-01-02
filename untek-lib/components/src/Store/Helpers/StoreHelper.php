<?php

namespace Untek\Lib\Components\Store\Helpers;

use Untek\Lib\Components\Store\StoreFile;

class StoreHelper
{

    public static function load($fileName = null, $key = null, $default = null, string $driver = null)
    {
        $store = new StoreFile($fileName, $driver);
        $data = $store->load($key);
        $data = $data !== null ? $data : $default;
        return $data;
    }

    public static function save($fileName, $data, string $driver = null): void
    {
        $store = new StoreFile($fileName, $driver);
        $store->save($data);
    }
}
