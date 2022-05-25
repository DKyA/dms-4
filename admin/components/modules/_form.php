<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<form action="<?=$_SERVER['PHP_SELF']?>" <?=$attributes['method']?> class="c-form c-form--<?=$attributes['class']?>" id='<?=$id?>'>
	<h1 class="c-form__headline"><?=$data[0]?></h1>
	<div class="c-form__body">
	<?php
		apply_modules($modules);
	?>
	</div>
</form>
