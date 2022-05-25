<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<a href="<?=$attributes['href']?>" <?=$attributes['rel']?> class="a-link a-link<?=$attributes['class']?>"><?=$data[0]?>
<?php
    apply_modules($modules);
?>
</a>
