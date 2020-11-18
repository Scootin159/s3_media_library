<?php namespace s3ml;

require_once('includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);

switch(isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null)
{    
    default:
        do_login_default();
    break;    
    case 'login':
        do_login();
    break;
    case 'logout':
        do_logout();
    break;
    case 'set_password':    
        do_set_password();
    break;
}

function do_login_default() : void
{
    Output::HTML('login.html');
}

function do_login() : void
{
    Loader::LoadLevel(Loader::LOAD_LEVEL_LOGINS);
    if(Logins::LoginUser($_REQUEST['username'], $_REQUEST['password']))
    {
        Output::Redirect('/');
    } else {
        Output::Error('Invalid Login');
    }
}

function do_logout() : void
{
    Loader::LoadLevel(Loader::LOAD_LEVEL_LOGINS);
    Logins::LogoutUser();
    Output::Redirect('/');
}

function do_set_password() : void
{
    Loader::LoadLevel(Loader::LOAD_LEVEL_LOGINS);
    Logins::SetPassword($_REQUEST['userid'], $_REQUEST['password']);
    Output::JSON("Success");
}