<?php namespace s3ml;

class Database
{
    public static function GetCollection (string $collection_name) : \MongoDB\Collection
    {
        $client = new \MongoDB\Client(SETTING_DB_CONNECTION_STRING);
        return $client->selectCollection(SETTING_DB_NAME, $collection_name);
    }
}