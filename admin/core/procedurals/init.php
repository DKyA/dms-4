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
        global $db, $PI;

        $this -> nested = [];
        if ($PI -> ref == 'settings') {
            $statement = $db -> prepare("SELECT * FROM component_settings WHERE affiliation = ?");
        }
        else {
            $statement = $db -> prepare("SELECT * FROM components WHERE affiliation = ?");
        }
        $statement -> execute([$this -> original['id']]);

        foreach ($statement as $row) {
            array_push($this -> nested, new Component($row));

        }

    }

}

if ($PI -> ref == 'settings') {
    $statement = $db -> prepare("SELECT * FROM component_settings");
    $statement -> execute();
}
else {
    $statement = $db -> prepare("SELECT * FROM components WHERE page = ?");
    $statement -> execute([$PI -> info['id']]);
}

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
            ?>
                <section class="l-component">
            <?php
            require $path['components'] . "modules/_{$dest}.php";
            ?>
                </section>
            <?php
            continue;
        }
        require $path['html'] . "submodules/_{$dest}.html";
    }

}
?>
<main class="l-main">
<?php 
apply_module($components);
?>
</main>