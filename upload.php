<?php namespace s3ml;

require_once('includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);

switch(isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null)
{   
    default:
        do_show_upload_form();
    break;     
    case 'upload_file':
        do_upload_file();
    break;
}

function do_show_upload_form()
{
    Output::HTML('upload.html');
}

function do_upload_file()
{
    require_once('api/files.php');

    do_upload();

    do_show_upload_form();
}