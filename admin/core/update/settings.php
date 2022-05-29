<?php
// Each setting will have its own accordion.
// This little plugin will check whether or not assigned component exists.
// If not, it will create one.
// The settings will have its own table.
// Utilisation of binary search

class Updates {

    private $settings;
    private $pages;
    private $settings_corr_page;

    public function __construct() {
        global $db;

        // Querying setting components
        $statement = $db -> prepare("SELECT * FROM component_settings");
        $statement -> execute();
        $this -> settings = [];
        $this -> settings_corr_page = [];
        foreach ($statement as $row) {
            array_push($this -> settings, $row);

            // Zrychlení iterace pro aktualizaci accordionů
            if (isset($row['corr_page'])) {
                array_push($this -> settings_corr_page, $row['corr_page']);
            }
        }

        // Querying pages for wrappers
        $statement = $db -> prepare("SELECT id, ref, name FROM pages WHERE in_settings = 1");
        $statement -> execute();
        $this -> pages = [];
        // Preparation of attribute insertion
        foreach ($statement as $value) {
            array_push($this -> pages, $value);
            // Inserting attribute

            // Binary search 
            if(StaticFunctions::binary_search($this -> settings_corr_page, $value['id'])) continue;
            $this -> update_page_accordions($value);

        }

    }

    private function update_page_accordions($page) {
        global $db;

        $prefill = [7, "{$page['ref']}-setup", json_encode(["Stránka {$page['name']}"]), json_encode(['type' => 'settings']), $page['id']];

        $statement = $db -> prepare("INSERT INTO component_settings (type, ref, data, attributes, corr_page) VALUES(?, ?, ?, ?, ?)");
        $statement -> execute($prefill);

    }

}

new Updates();
