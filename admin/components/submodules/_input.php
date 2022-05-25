<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<div class="c-input">
    <label for="<?=$id?>" class="c-input__label"><?=$data[0]?></label>
    <input <?=$attributes['type']?> name="<?=$id?>" id='<?=$id?>' class="c-input__field c-input__field<?=$attributes['class']?>" <?=$attributes['placeholder']?> <?=$attributes['required']?>>
</div>