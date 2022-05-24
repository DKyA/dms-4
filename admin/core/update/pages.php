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

$special_prefill = (function() {
    global $path;
    $file = "{$path['static']}docs/special_confs.txt";
    $fin = fopen($file, 'r');
    $res = fread($fin, filesize($file));
    fclose($fin);
    return $res;
})();

foreach ($statement as $row) {
    if (!$row['autoplace']) continue;
    if (is_dir($dir . $row['ref'])) continue;
    if ($row['special']) {
        $dir = $path['core'] . 'specials/confs/';
        $file = fopen($dir . $row['ref'], 'w');
        fwrite($file, $special_prefill);
        fclose($file);
        continue;
    }
    mkdir($dir . $row['ref']);
    $file = fopen($dir . $row['ref'] . '/index.php', 'w');
    fwrite($file, $init_text($row['ref']));
    fclose($file);

}
