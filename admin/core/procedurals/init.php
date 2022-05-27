<?php

// Ty budeš teoreticky jenom a pouze číst DB a házet komponenty...

class Component {

    private $original;
    private $data;

    function __construct($component) {

        $this -> original = $component;

        $this -> get_data();
        $this -> create();

    }

    private function get_data() {
        global $db;

        $statement = $db -> prepare("SELECT * FROM component_list WHERE id = ?");
        $statement -> execute([$this -> original['type']]);

        $this -> data = $statement -> fetch(PDO::FETCH_ASSOC);

    }

    private function create() {
        global $path;

        $placeholder = (function() {
            global $path;

            $filename = ($this -> data['nestable']) ? 'new_component.txt' : 'new_subcomponent.txt';

            $file = fopen($path['docs'] . $filename, 'r');
            $data = fread($file, filesize($path['docs'] . $filename));
            fclose($file);

            return $data;

        })();

        creator($path['components'] . 'modules/_' . $this -> data['config'] . '.php', $placeholder);

    }

    function get_subcomponents() {
        // Projde nestable a nested, které jsou na to navázané
        // V DB struktuře bude potřeba zkopírovat genezi Specials
    }

    // function get_attributes() {} ???

}


$statement = $db -> prepare("SELECT * FROM components WHERE page = ?");
$statement -> execute([$PI -> info['id']]);

$components = [];

foreach($statement as $component_props) {

    array_push($components, new Component($component_props));

    // $component_data = 

}

function apply_module() {
    
}