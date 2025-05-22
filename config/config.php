<?php
if (!defined('DB_HOST')) {
    // define('DB_HOST', 'localhost:3308');
    define('DB_HOST', 'travel-terrain');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    // define('DB_PASS', '');
    define('DB_PASS', 'x1M7T1IYK1EvuquuhwmYQhRj74jPiKk7oR4vDsaQ3aEAapuPYG7JuKW6qOVXzxfB');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'travel');
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

