<?php namespace s3ml;

require_once(__DIR__ . '/loader.inc.php');

function debug_die($var = "debug_die reached")
{
    if(is_ajax())
    {
        header('Content-type: text/javascript');
        echo 'console.log(';
        echo json_encode($var);
        echo ');';
        die();
    } else {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die();
    }
}

function is_ajax() {
    return 
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}