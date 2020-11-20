<?php namespace s3ml;

require_once(__DIR__ . '/../includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_OUTPUT);
Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);
Loader::LoadLevel(Loader::LOAD_LEVEL_USER);

use \MongoDB\BSON\ObjectId;

switch(isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null)
{    
    case 'get_thumbnails':
        do_get_thumbnails();
    break;
    case 'get_thumbnail':
        do_get_thumbnail();
    break;
}

function do_get_thumbnails()
{
    $db_images = Database::GetCollection("media")->find(
        ['userid' => User::GetUserId()],
        ['projection' => ['id' => 1]]);

    $images = [];
    foreach($db_images as $image)
    {
        $images[] = $image->_id->__toString();
    }

    Output::JSON(['images' => $images]);
}

function do_get_thumbnail()
{
    $thumbnail = Database::GetCollection("media")->findOne(
        [
            'userid' => User::GetUserId(),
            '_id' => new ObjectId($_REQUEST['id'])
        ],
        ['projection' => [
            'thumbnail' => 1
        ]]);

    header('Content-Type: ' . $thumbnail->thumbnail->content_type);
    echo(base64_decode($thumbnail->thumbnail->data));
    exit();
}