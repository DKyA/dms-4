<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<div class="c-button c-button<?=$attributes['class']?>">
    <button <?=$attributes['type+']?> class="c-button__self"><?=$data[0]?></button>
</div>