<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<form action="<?=$_SERVER['PHP_SELF']?>" <?=$attributes['method']?> class="c-u-form c-u-form<?=$attributes['class']?>" id='<?=$id?>'>

	<div class="c-u-form__s-controllers">
		<button class="c-u-form__button c-u-form__button--danger" type="button" role="button" title="Odstranit prvek... (Otevře rozcestník)" aria-label="Odstranit prvek nastavení (otevře rozcestník)"></button>
		<button class="c-u-form__button c-u-form__button--warning" type="submit" role="submit" title="Uložit" aria-label="Uloží hodnoty nastavení"></button>
		<button class="c-u-form__button c-u-form__button--green" type="button" role="button" title="Přidat prvek... (Otevře rozcestník)" aria-label="Přidat prvek nastavení... (Otevře rozcestník)"></button>
	</div>

    <div class="c-u-form__inputs">
        <?php
            apply_module($nested);
        ?>
    </div>


</form>
