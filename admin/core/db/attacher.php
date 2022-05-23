<?php

$dsn = "mysql:host=localhost;dbname=DMS-4;charset=utf8";
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
];
try {
    $db = new PDO($dsn, "root", "root", $options);
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Nastala chyba...'); //something a user can understand
}
