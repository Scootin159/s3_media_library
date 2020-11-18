<?php namespace s3ml;

require __DIR__ . '/composer/vendor/autoload.php';

class Loader
{
    private const LOAD_LEVEL_0 = 0;
    private const LOAD_LEVEL_1 = 1;
    private const LOAD_LEVEL_2 = 2;
    private const LOAD_LEVEL_3 = 3;

    public const LOAD_LEVEL_SETTINGS = Loader::LOAD_LEVEL_0;
    public const LOAD_LEVEL_OUTPUT = Loader::LOAD_LEVEL_1;
    public const LOAD_LEVEL_DATABASE = Loader::LOAD_LEVEL_2;
    public const LOAD_LEVEL_SESSION = Loader::LOAD_LEVEL_2;    
    public const LOAD_LEVEL_LOGINS = LOADER::LOAD_LEVEL_2;

    private static $current_level = -1;

    public static function LoadLevel(int $desired_level)
    {
        if($desired_level > Loader::$current_level)
        {
            for($i = Loader::$current_level + 1; $i <= $desired_level; $i++)
            {
                Loader::InitLevel($i);
                Loader::$current_level = $i;
            }
        }
    }

    private static function InitLevel(int $level_to_load)
    {
        switch($level_to_load)
        {
            case Loader::LOAD_LEVEL_0:
                require_once(__DIR__ . '/../config/config.inc.php');
                require_once(__DIR__ . '/settings.inc.php');
            break;
            case Loader::LOAD_LEVEL_1:
                require_once(__DIR__ . '/output.inc.php');
            break;
            case Loader::LOAD_LEVEL_2:
                require_once(__DIR__ . '/database.inc.php');
                require_once(__DIR__ . '/session.inc.php');
                require_once(__DIR__ . '/logins.inc.php');
            break;
            case Loader::LOAD_LEVEL_3:
                
            break;
        }
    }
}