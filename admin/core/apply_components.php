<?php

function apply_module($modules) {
    global $path;
    // Rekurzivní funkce, která bude vlastně procházet každým affiliation nodem stromu a dodávat dané komponenty.
    // $module je teda scope a musí se s ním tak pracovat
    // Takže mě bude vyloženě vždycky zajímat jenom a pouze jedna vrstva.


    foreach($modules as $module) {

        $file_info = (function($module) {
            global $path;
            if (is_nestable($module)) {
                $component_file = $path['modules'] . '_' . $module -> element . '.php';
                $placeholder = doc_reader('new_component.txt');
                return [$component_file, $placeholder];
            }

            $component_file = $path['components'] .'submodules/_' . $module -> element . '.php';
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



function is_nestable($module) {

    global $db;

    $statement = $db -> prepare("SELECT nestable FROM component_list WHERE desc_db = :ide");
    $statement -> bindValue(":ide", "c_{$module -> element}", PDO::PARAM_STR);
    $statement -> execute();

    $nestable = $statement -> fetch(PDO::FETCH_COLUMN);
    return $nestable;

}

// Tohle bude v budoucnu zanestěné v daném layoutu / komponentě