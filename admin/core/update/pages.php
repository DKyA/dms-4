<?php

global $db, $path;

$statement = $db -> prepare("SELECT * FROM pages");
$statement -> execute();

$dir = $path['categories'];

$init_text = (function($name){ 
    
    return '<?php
require_once "../manifest.php";

$reference = "' . $name . '";  // Odkaz

require_once $path["core"] . "base.php";
';
});

foreach ($statement as $row) {
    if (!$row['autoplace']) continue;
    if (is_dir($dir . $row['ref'])) continue;
    mkdir($dir . $row['ref']);
    $file = fopen($dir . $row['ref'] . '/index.php', 'w');
    fwrite($file, $init_text($row['ref']));
    fclose($file);
}
