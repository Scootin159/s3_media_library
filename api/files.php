<?php namespace s3ml;

require_once(__DIR__ . '/../includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);
Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);
Loader::LoadLevel(Loader::LOAD_LEVEL_USER);

use \MongoDB\BSON\ObjectId;
use \MongoDB\BSON\UTCDateTime;

switch(isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null)
{
    case 'upload':
        do_upload();
    break;
    case 'download':
        do_download();
    break;
    case 'delete':
        do_delete();
    break;
}

function do_upload()
{
    $ids = [];

    foreach($_FILES as $file)
    {
        $file_data = file_get_contents($file['tmp_name']);

        Loader::LoadLevel(Loader::LOAD_LEVEL_MEDIA);
        $exif = Media::ReadEXIFData($file['tmp_name']);
        $mime_type = Media::GetMimeType($file['tmp_name']);
        if($mime_type === null) { die("Invalid MIME type"); }
        $thumbnail = Media::CreateThumbnail($file_data);        

        Loader::LoadLevel(Loader::LOAD_LEVEL_USER);
        Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);
        $result = Database::GetCollection("media")->insertOne(            
            [
                'userid' => User::GetUserId(),
                'filename' => $file['name'],
                'upload_date' => new UTCDateTime(),
                'exif' => $exif,
                'content_type' => $mime_type,
                'thumbnail' => [
                    'data' => $thumbnail,
                    'content_type' => 'image/webp'
                ]
            ]
        );

        $key = $result->getInsertedId()->__toString();

        Loader::LoadLevel(Loader::LOAD_LEVEL_S3);
        S3::UploadFile($key, $mime_type, $file_data);

        $ids[] = $key;
    }

    return $ids;
}

function do_download()
{
    // Do a quick DB check to ensure we're the proper user for this file
    $file = Database::GetCollection("media")->findOne(
        [
            'userid' => User::GetUserId(),
            '_id' => new ObjectId($_REQUEST['id'])
        ],
        ['projection' => [
            '_id' => 1,
            'content_type' => 1,
            'filename' => 1
        ]]);

    header('Content-Type: ' . $file->content_type);
    header('Content-Disposition: attachment; filename="' . $file->filename);
    
    Loader::LoadLevel(Loader::LOAD_LEVEL_S3);
    echo S3::DownloadFile($file->_id->__toString());
}

function do_delete()
{
    // See if the item is in the database for this user, then delete it
    $delete_result = Database::GetCollection("media")->deleteOne(
        [
            'userid' => User::GetUserId(),
            '_id' => new ObjectId($_REQUEST['id'])
        ],
        ['projection' => [
            '_id' => 1
        ]]);
    
    if ($delete_result->getDeletedCount() === 1) {
        Loader::LoadLevel(Loader::LOAD_LEVEL_S3);
        echo S3::DeleteFile($_REQUEST['id']);
    }
}