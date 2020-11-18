<?php namespace s3ml;

class Settings
{
    public static function DefineDefaults() : void
    {
        // Some Global Settings
        self::DefineDefault('SETTING_URL_ROOT', 'http://localhost');

        // Database Settings
        self::DefineDefault('SETTING_DB_CONNECTION_STRING', 'mongodb://localhost/');
        self::DefineDefault('SETTING_DB_NAME', 's3ml');

        // Disable some admin settings not ready for live use
        self::DefineDefault('SETTING_ENABLE_SET_PASSWORD', false);
    }

    private static function DefineDefault(string $setting, $value) : void
    {
        if(!defined($setting))
        {
            define($setting, $value);
        }
    }
}

Settings::DefineDefaults();