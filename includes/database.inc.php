<?php namespace s3ml;

class Database
{
    public static function GetCollection (string $collection_name) : \MongoDB\Collection
    {
        $client = new \MongoDB\Client(DB_CONNECTION_STRING);
        return $client->selectCollection(DB_NAME, $collection_name);
    }
}