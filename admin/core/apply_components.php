<?php

function apply_module($module) {
    global $path;
    // Rekurzivní funkce, která bude vlastně procházet každým affiliation nodem stromu a dodávat dané komponenty.
    // $module je teda scope a musí se s ním tak pracovat
    // Takže mě bude vyloženě vždycky zajímat jenom a pouze jedna vrstva.

    // print_r($module -> element);
    // print_r($module -> load_order);

    $file_info = (function($module) {
        global $path;
        if ($module -> load_order) {
            $component_file = $path['modules'] . $module -> element . '.php';
            $placeholder = doc_reader('new_component.txt');
            return [$component_file, $placeholder];
        }
        $component_file = $path['components'] .'submodules/' . $module -> element . '.php';
        $placeholder = doc_reader('new_subcomponent.txt');
        return [$component_file, $placeholder];

    })($module);



    creator(...$file_info);
    require $file_info[0];

}

apply_module($layout);
// Tohle bude v budoucnu zanestěné v daném layoutu / komponentě