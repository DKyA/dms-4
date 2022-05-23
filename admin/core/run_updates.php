<?php
    $dir = "{$path['core']}update/";
    if (is_dir($dir)) {
        if($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false){
                if ($file != '.' && $file != '..' && $file != 'README.md') {
                    require $dir . $file;
                }
            }
        }
    }