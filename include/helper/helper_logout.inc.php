<!-- TODO Comment -->

<?php // inspired by https://www.php.net/session_destroy

//Don't include the php header via this path, if the php header is already loaded.
if(!defined("ROOT_DIR"))
    require_once "../site_php_head.inc.php";

// Initialise the session.
session_start();

// Clear all session variables.
$_SESSION = array();

// If the session is to be deleted, also delete the session cookie.
// Attention: This deletes the session, not only the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finally, delete the session.
session_destroy();

header("LOCATION: " . ROOT_DIR);
die();