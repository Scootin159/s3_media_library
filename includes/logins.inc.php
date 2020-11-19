<?php namespace s3ml;

Loader::LoadLevel(Loader::LOAD_LEVEL_SESSION);

use \MongoDB\BSON\ObjectId;

class Logins
{
    private const SESSION_USERID = "userid";

    public static function RequireLogin()
    {
        if(!isset($_SESSION[Logins::SESSION_USERID]))
        {
            Output::Redirect('/login.php');
        }
    }

    public static function GetUserId() : ObjectId
    {
        self::RequireLogin();
        return $_SESSION[Logins::SESSION_USERID];
    }

    public static function SetPassword(string $userid, string $password)
    {
        Loader::LoadLevel(Loader::LOAD_LEVEL_SETTINGS);
        if(!SETTING_ENABLE_SET_PASSWORD) { die("This feature is currently disabled"); }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);

        $result = Database::GetCollection("users")->updateOne(
            ['_id' => new ObjectId($userid)],
            ['$set' => ['password_hash' => $password_hash]]
        );
    }

    public static function LoginUser(string $username, string $password) : bool
    {
        Loader::LoadLevel(Loader::LOAD_LEVEL_DATABASE);

        $user_info = Database::GetCollection("users")->findOne(
            ['username' => strtolower($username)],
            ['projection' => ['password_hash' => 1]]);

        if($user_info === null) {
            Output::Error("Invalid login");
        }

        if(!password_verify($password, $user_info->password_hash))
        {
            Output::Error("Invalid Login");
        }

        Loader::LoadLevel(Loader::LOAD_LEVEL_SESSION);

        $_SESSION[Logins::SESSION_USERID] = $user_info->_id;

        return true;
    }

    public static function LogoutUser()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}