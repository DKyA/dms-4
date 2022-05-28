<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<div class="c-card_carousel">
    <p class="c-card_carousel__caption">
        <?=$data[0]?>
    </p>
    <div class="c-card_carousel__inner">
        <button class="c-card_carousel__arrow">
            <img src="/admin/static/icons/caret-left-fill.svg" alt="Levá šipka">
        </button>
        <div class="c-card_carousel__casing c-card_carousel__js">
            <?php
                apply_module($nested);
                // Dál už se z nějakého důvodu nic nevytiskne...
            ?>
        </div>
        <button class="c-card_carousel__arrow">
            <img src="/admin/static/icons/caret-right-fill.svg" alt="Pravá šipka">
        </button>
    </div>

</div>