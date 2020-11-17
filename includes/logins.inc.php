<?php namespace s3ml;

class Logins
{
    private const SESSION_USERID = "userid";

    public static function RequireLogin()
    {
        if(!isset($_SESSION[Logins::SESSION_USERID]))
        {
            Output::Redirect('login.php');
        }
    }
}