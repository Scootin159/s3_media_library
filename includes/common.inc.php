<?php namespace s3ml;

require_once(__DIR__ . '/loader.inc.php');

function debug_die($var = "debug_die reached")
{
    print_r($var);
    die();
}