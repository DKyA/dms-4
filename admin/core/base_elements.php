<?php
// Tady se budou připravovat definice základních elementů. Idea je taková, že se to pak pomocí jediné funkce zavolá
// To se pak vloží do obou initů.

function insert_nav() {
    global $PI, $path;

    if (!$PI -> in_nav) return;

    require $path['components'] . 'basic/nav.php';

}


function insert_infobar() {
    global $PI, $path;
    if (!$PI -> infobar) return;

    require $path['components'] . 'basic/infobar.php';

}
