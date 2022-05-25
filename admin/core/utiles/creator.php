<?php

function creator($path, $placeholder_content) {
    // Podívám se, jestli existuje => vytvořím

    if (file_exists($path)) return True;
    $file = fopen($path, 'w');
    fwrite($file, $placeholder_content);
    fclose($file);
    return False;

}

function doc_reader($doc) {
    global $path;
    $file = fopen($path['docs'] . $doc, 'r') or die("File {$doc} doesn't exist!!!");
    $res = fread($file, filesize($path['docs'] . $doc));
    fclose($file);

    return $res;

}