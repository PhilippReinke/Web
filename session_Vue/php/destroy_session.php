<?php
// Initialisierung der Session.
session_start();

// Löschen aller Session-Variablen.
$_SESSION = array();

// Session-Cookie löschen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"],
        $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Zum Schluss, löschen der Session.
session_destroy();
?>