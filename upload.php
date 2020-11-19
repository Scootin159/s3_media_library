<?php namespace s3ml;

require_once('includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);

switch(isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null)
{   
    default:
        do_show_login_form();
    break;     
    case 'upload_file':
        do_upload_file();
    break;
}

function do_show_login_form()
{
    Output::HTML('upload.html');
}

function do_upload_file()
{
    debug_die($_FILES);
}