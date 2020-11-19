<?php namespace s3ml;

require_once('includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);

use \MongoDB\BSON\ObjectId;
use \MongoDB\BSON\UTCDateTime;

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
    foreach($_FILES as $file)
    {
        $file_data = file_get_contents($file['tmp_name']);

        Loader::LoadLevel(Loader::LOAD_LEVEL_LOGINS);
        Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);
        $result = Database::GetCollection("media")->insertOne(            
            [
                'userid' => Logins::GetUserId(),
                'filename' => $file['name'],
                'upload_date' => new UTCDateTime()
            ]
        );

        $key = $result->getInsertedId()->__toString();

        Loader::LoadLevel(Loader::LOAD_LEVEL_S3);
        S3::UploadFile($key, $file_data);

        do_show_upload_form();
    }
}