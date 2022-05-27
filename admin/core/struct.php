<?php
// Inicializace základních struktur a větevní...

require $path['core'] . 'base_elements.php';

$base_class = ($PI -> in_nav) ? 'l-page--with_nav' : 'l-page--default';

?>
<div class="l-page <?=$base_class?>">
<?php
    insert_nav();
?>
<div class="l-content">
<?php
    insert_infobar();
    // Tohle bude složitější, asi nejlepší se inspirovat systémem 2.0

    if ($PI -> special) {
        require $path['core'] . 'specials/functions/init.php';
    }

    else {
        require $path['core'] . 'procedurals/init.php';
    }
?>
</div>
</div>
<?php
