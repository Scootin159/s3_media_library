<?php namespace s3ml;

use Aws\S3\S3Client;

class S3
{
    public static function UploadFile(string $key, $body)
    {
        Loader::LoadLevel(LOADER::LOAD_LEVEL_LOGINS);

        $userid = Logins::GetUserId()->__toString();

        $s3 = new S3Client(SETTING_S3_CONNECT_INFO);

        $insert = $s3->putObject([
            'Bucket' => SETTING_S3_BUCKET,
            'Key' => "$userid/$key",
            'Body' => $body
        ]);
    }
}