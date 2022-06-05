<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<div class="a-link" id="<?=$id?>">
    <a <?=$attributes['href']?> <?=$attributes['rel']?> class="a-link__self a-link__self<?=$attributes['class']?>"><?=$data[0]?>
    <?php
        apply_modules($modules);
    ?>
    </a>
</div>
