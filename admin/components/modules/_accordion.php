<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/

?>

<div class="c-accordion" accordion-self>
    <fieldset class="c-accordion__tab">
        <input type="checkbox" class="c-accordion__controller" role="button" id="<?= $id ?>">
        <label for="<?= $id ?>" class="c-accordion__clicker" role="button" aria-labelledby="<?=$id?>" accordion-button>
            <legend class="c-accordion__legend">
                <div class="c-accordion__headlines">
                    <a <?= $attributes['link'] ?> class="c-accordion__link" <?= $attributes['title'] ?> target="_blank" aria-label="Link na komponentu">
                        <img src="/admin/static/icons/link-45deg.svg" alt="Link na komponentu">
                    </a>
                    <h4><?= $data[0] ?></h4>
                </div>

                <img class="c-accordion__unwrap-icon" src="/admin/static/icons/chevron-down.svg" alt="Rozbalit accordion">
            </legend>
        </label>
        <div class="c-accordion__body" accordion-body>
            <div class="c-accordion__wrapper" accordion-last>
                <?php
                apply_module($nested);
                ?>
            </div>
        </div>
    </fieldset>
</div>