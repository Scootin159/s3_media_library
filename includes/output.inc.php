<?php namespace s3ml;

class Output
{
    public static function Redirect(string $sub_url) : void
    {
        $url = SETTING_URL_ROOT . $sub_url;
        header("Location: $url");
        exit();
    }

    public static function Error(string $error) : variant_mod
    {
        Output::JSON($error);
    }

    public static function HTML(string $template_name) : void
    {
        header('Content-Type: text/html');
        $fp = fopen(__DIR__ . '/../resources/dist/html/' . $template_name, 'r');
        fpassthru($fp);
        exit();
    }

    public static function JSON($json) : void
    {
        header('Content-Type: application/json');
        echo(json_encode($json));
        exit();
    }
}