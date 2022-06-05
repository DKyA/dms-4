<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<section class="c-accordion">
    <div class="c-accordion__headbar">
        <h1><?=$data[0]?></h1>
        <a <?=$attributes['link']?>>V</a>
        <button class="c-accordion__control" accordion-toggle>X</button>
    </div>
    <div class="c-accordion__body">
        <?php
            apply_module($nested);
        ?>
    </div>
</section>

