<?php namespace s3ml;

require_once(__DIR__ . '/loader.inc.php');

function debug_die($var = "debug_die reached")
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}