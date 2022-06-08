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
		<button class="c-u-form__button c-u-form__button--danger" type="submit" role="submit" title="" aria-label="">X</button>
		<button class="c-u-form__button c-u-form__button--warning" type="submit" role="submit" title="" aria-label="">X</button>
		<button class="c-u-form__button c-u-form__button--green" type="submit" role="submit" title="" aria-label="">X</button>
	</div>

	<?php
		apply_module($nested);
	?>

</form>
