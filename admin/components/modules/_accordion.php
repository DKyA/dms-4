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
        <button for="<?= $id ?>" class="c-accordion__clicker" aria-label="Otevřít sekci nastavení" accordion-button>
            <legend class="c-accordion__legend">
                <div class="c-accordion__headlines">
                    <a <?= $attributes['link'] ?> class="c-accordion__link" <?= $attributes['title'] ?> target="_blank" <?=$attributes['aria-label']?>>
                        <img src="/admin/static/icons/link-45deg.svg" alt="<?= $attributes['title'] ?>">
                    </a>
                    <h<?=$attributes['level']?>><?= $data[0] ?></h<?=$attributes['level']?>>
                </div>

                <img class="c-accordion__unwrap-icon" src="/admin/static/icons/chevron-down.svg" alt="Rozbalit accordion" accordion-icon>
            </legend>
        </button>
        <div class="c-accordion__body" accordion-body accordion-last>
            <?php
            apply_module($nested);
            ?>
        </div>
    </fieldset>
</div>