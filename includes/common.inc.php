<?php namespace photos;

require_once(__DIR__ . '/../config/config.inc.php');
require_once(__DIR__ . '/loader.inc.php');

function debug_die($var = "debug_die reached")
{
    print_r($var);
    die();
}