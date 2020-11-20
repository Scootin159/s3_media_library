<?php namespace s3ml;

use Aws\S3\S3Client;

class S3
{
    public static function UploadFile(string $key, string $content_type, $body)
    {
        Loader::LoadLevel(LOADER::LOAD_LEVEL_USER);

        $userid = User::GetUserId()->__toString();

        $s3 = new S3Client(SETTING_S3_CONNECT_INFO);

        $s3->putObject([
            'Bucket' => SETTING_S3_BUCKET,
            'Key' => "$userid/$key",
            'ContentType' =>  $content_type,
            'Body' => $body
        ]);
    }

    public static function DownloadFile(string $key) : string
    {
        Loader::LoadLevel(LOADER::LOAD_LEVEL_USER);

        $userid = User::GetUserId()->__toString();

        $s3 = new S3Client(SETTING_S3_CONNECT_INFO);

        $object = $s3->getObject([
            'Bucket' => SETTING_S3_BUCKET,
            'Key' => "$userid/$key"
        ]);

        return $object['Body'];
    }

    public static function DeleteFile(string $key)
    {
        Loader::LoadLevel(LOADER::LOAD_LEVEL_USER);

        $userid = User::GetUserId()->__toString();

        $s3 = new S3Client(SETTING_S3_CONNECT_INFO);

        $object = $s3->deleteObject([
            'Bucket' => SETTING_S3_BUCKET,
            'Key' => "$userid/$key"
        ]);
    }
}