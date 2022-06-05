<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/

?>

<section class="c-accordion c-accordion<?=$attributes['class']?>" accordion-selector>
    <div class="c-accordion__self">
    <div class="c-accordion__headbar">
        <legend class="c-accordion__legend">
            <a <?=$attributes['link']?> class="c-accordion__link" <?=$attributes['title']?> target="_blank">
                <img src="/admin/static/icons/link-45deg.svg" alt="Link na komponentu">
            </a>
            <h3><?=$data[0]?></h3>
        </legend>
        <button class="c-accordion__control" accordion-toggle title="Rozbalit obsah">
            <img src="/admin/static/icons/chevron-down.svg" alt="Rozbalit accordion">
        </button>
    </div>
    <div class="c-accordion__body">
        <?php
            apply_module($nested);
        ?>
    </div>

    </div>
</section>

