<?php
/*
Dostupné proměnné:

$data => texty
$attributes => atributy do HTML tagů (asociativní pole)
$nested => další komponenty

*/
?>

<?=$data[0]?>
<div>
    <?php
        apply_module($nested);
    ?>
</div>