<?php

if ($PI -> in_nav) {
    require $path['components'] . 'modules/nav.php';
}

if ($PI -> infobar) {
    require $path['components'] . 'modules/infobar.php';
}

if ($PI -> special) {
    require $path['core'] . 'specials/functions/init.php';
}
else {
    echo 'I follow DB';
}
