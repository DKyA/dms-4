<?php

function apply_module($modules) {
    global $path;
    // Rekurzivní funkce, která bude vlastně procházet každým affiliation nodem stromu a dodávat dané komponenty.
    // $module je teda scope a musí se s ním tak pracovat
    // Takže mě bude vyloženě vždycky zajímat jenom a pouze jedna vrstva.


    foreach($modules as $module) {

        $file_info = (function($module) {
            global $path;
            if ($module -> load_order) {
                $component_file = $path['modules'] . $module -> element . '.php';
                $placeholder = doc_reader('new_component.txt');
                return [$component_file, $placeholder];
            }

            separate();
            $component_file = $path['components'] .'submodules/' . $module -> element . '.php';
            $placeholder = doc_reader('new_subcomponent.txt');
            return [$component_file, $placeholder];

        })($module);

        $data = $module -> data;
        $attributes = $module -> attributes;
        $nested = $module -> load_order;

        creator(...$file_info);
        require $file_info[0];

    }

}

// Tohle bude v budoucnu zanestěné v daném layoutu / komponentě