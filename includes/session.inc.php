<?php namespace s3ml;

require_once(__DIR__ . '/database.inc.php');

class DatabaseSessionHandler implements \SessionHandlerInterface
{
    private $collection;

    public function __construct()
    {
        $this->collection = Database::GetCollection("sessions");
    }

    public function open($_save_path, $_name) : bool
    {
        return true;
    }

    public function close() : bool
    {
        return true;
    }

    public function read($session_id) : string
    {
        $session = $this->collection->findOne(['_id' => $session_id], ['projection' => ['data' => 1]]);

        if ($session) {
            return $session['data']->getData();
        } else {
            return '';
        }
    }

    public function write($session_id, $data) : bool
    {
        $session = [
            '_id' => $session_id,
            'data' => new Binary($data, Binary::TYPE_OLD_BINARY),
            'last_accessed' => new UTCDateTime(floor(microtime(true) * 1000))
        ];

        try {
            $this->collection->replaceOne(
                ['_id' => $session_id],
                $session, 
                ['upsert' => true]);

            return true;
        } catch (MongoDBException $e) {
            return false;
        }
    }

    public function destroy($id) : bool
    {
        try {
            $this->collection->deleteOne(['_id' => $id]);

            return true;
        } catch (MongoDBException $e) {
            return false;
        }
    }

    public function gc($maxlifetime) : int
    {
        $lastAccessed = new UTCDateTime(floor((microtime(true) - $maxlifetime) * 1000));

        try {
            $delete_result = $this->collection->deleteMany([
                'last_accessed' => ['$lt' => $lastAccessed]]);
            return $delete_result->getDeletedCount();
        } catch (MongoDBException $e) {
            return false;
        }
    }
}

session_set_save_handler(new DatabaseSessionHandler());
session_start();