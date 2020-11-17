<?php namespace s3ml;

class Output
{
    public static function Redirect(string $url)
    {
        header("Location: $url");
        exit();
    }

    public static function HTML(string $template_name)
    {
        header('Content-Type: text/html');
        $fp = fopen(__DIR__ . '/../resources/dist/html/' . $template_name, 'r');
        fpassthru($fp);
        exit();
    }
}