<?php

function run($general_dest, $dir) {
    global $path;
    $dir = "{$path[$general_dest]}{$dir}/";
    if (is_dir($dir)) {
        if($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false){
                if ($file != '.' && $file != '..' && $file != 'README.md' && $file != 'include_all.php') {
                    require $dir . $file;
                }
            }
        }
    }
}