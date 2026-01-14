<?php

class Config
{
    private static $config;

    public static function load()
    {
        if (!self::$config) {
            $path = __DIR__ . '/../config/config.xml';
            self::$config = simplexml_load_file($path);
        }
        return self::$config;
    }

    public static function getMaxBookingPerDay()
    {
        $config = self::load();
        return (int) $config->rules->maxBookingPerDay;
    }
}
