<?php

// Ty budeš teoreticky jenom a pouze číst DB a házet komponenty...

require $path['core'] . 'specials/functions/module.php';

class Component extends Module {

    public $original;
    public $data;
    public $nested;

    public $attributes;

    function __construct($component) {

        $this -> original = $component;

        $this -> get_data();
        $this -> create();
        $this -> get_subcomponents();

        $this -> attributes = $this -> inner_attributes(json_decode($this -> original['attributes'], True));

    }

    private function get_data() {
        global $db;

        $statement = $db -> prepare("SELECT * FROM component_list WHERE id = ?");
        $statement -> execute([$this -> original['type']]);

        $this -> data = $statement -> fetch(PDO::FETCH_ASSOC);

        // Error hláška, pokud by se stalo, že bych se pokoušel zanestit něco, co zanestitelné není.
        if ($this -> data['nestable'] and $this -> original['affiliation']) die("Critical error, {$this -> data['config']} is not nestable, child given!");

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

        if ($this -> data['nestable']) {
            creator($path['components'] . 'modules/_' . $this -> data['config'] . '.php', $placeholder);
            return;
        }

        creator($path['html'] . 'submodules/_' . $this -> data['config'] . '.html', $placeholder);

    }

    public function get_subcomponents() {
        global $db;

        $this -> nested = [];
        $statement = $db -> prepare("SELECT * FROM components WHERE affiliation = ?");
        $statement -> execute([$this -> original['id']]);

        foreach ($statement as $row) {

            array_push($this -> nested, new Component($row));

        }

    }

}


$statement = $db -> prepare("SELECT * FROM components WHERE page = ?");
$statement -> execute([$PI -> info['id']]);

$components = [];

foreach($statement as $component_props) {

    if ($component_props['affiliation']) continue;
    array_push($components, new Component($component_props));

}

function apply_module($components) {

    if (!isset($components)) return;
    // Konec...

    foreach ($components as $component) {

        global $path, $text;

        $data = json_decode($component -> original['data'], True);
        $attributes = $component -> attributes;
        $id = $component -> original['ref']; // Rozšiřitelné skrz Ids::unique_id($base)
        $dest = $component -> data['config'];
        $nested = $component -> nested;

        if ($component -> data['nestable'] === 1) {
            require $path['components'] . "modules/_{$dest}.php";
            continue;
        }
        require $path['html'] . "submodules/_{$dest}.html";
    }

}

apply_module($components);