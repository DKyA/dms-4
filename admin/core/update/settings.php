<?php
// Each setting will have its own accordion.
// This little plugin will check whether or not assigned component exists.
// If not, it will create one.
// The settings will have its own table.
// Utilisation of binary search

class Updates {

    private $settings;
    private $pages;
    private $components;
    private $settings_corr_page;
    private $settings_corr_element;

    public function __construct() {
        global $db;

        // Querying setting components
        $statement = $db -> prepare("SELECT * FROM component_settings");
        $statement -> execute();
        $this -> settings = [];
        $this -> settings_corr_page = [];
        $this -> settings_corr_element = [];

        foreach ($statement as $row) {
            array_push($this -> settings, $row);

            // Zrychlení iterace pro aktualizaci accordionů
            if (isset($row['corr_page'])) {
                array_push($this -> settings_corr_page, $row['corr_page']);
            }
            if (isset($row['corr_element'])) {
                array_push($this -> settings_corr_element, $row['corr_element']);
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

        // Component handling::
        // Zase bude potřeba si nahoře definovat setting_corr_components
        // Úplně stejný princip, asi jiné query. možná půjde něco recyklovat. Možná ne.

        // Seznam komponent:
        $statement = $db -> prepare("SELECT A.* FROM components A WHERE A.page IN (SELECT B.in_settings FROM pages B WHERE B.id = A.page)");
        $statement -> execute();

        $this -> components = [];

        foreach($statement as $value) {
            array_push($this -> components, $value);

            if(StaticFunctions::binary_search($this -> settings_corr_element, $value['id'])) continue;

            $this -> update_components($value);

        }

        // Asi bych nejdřív komponenty vytvořil => pouze tam, kde to nefunguje
        // Byla by tam i procházecí funkce, která by se určitě volala jenom na stisknutí tlačítka => moc dlouhý... 

        // -- Okej, asi stačí.

    }


    private function update_page_accordions($page) {

        $prefill = [
            7,
            "{$page['ref']}-setup", 
            NULL,
            json_encode(["Stránka {$page['name']}"]), 
            json_encode(['type' => 'settings']), 
            $page['id'],
            NULL
        ];

        $this -> inserter($prefill);

    }


    private function update_components($component) {
        // Actually, tady bude sub-accordion...

        if (!$component['data']) {
            $component['data'] = json_encode([]);
        }
        if (!$component['attributes']) {
            $component['attributes'] = json_encode([]);
        }

        $affiliation = (function($component) {
            global $db;

            $statement = $db -> prepare("SELECT id FROM component_settings WHERE corr_page = ?");
            $statement -> execute([$component['page']]);

            return $statement -> fetch(PDO::FETCH_COLUMN);

        })($component);

        $name = (function($component) {
            global $db;
            
            $statement = $db -> prepare("SELECT name FROM component_list WHERE id = ?");
            $statement -> execute([$component['type']]);

            return $statement -> fetch(PDO::FETCH_COLUMN);

        })($component);


        $prefill = [
            7,
            "{$component['ref']}-setup",
            $affiliation,
            json_encode(["Komponenta {$name}"]),
            json_encode(['type' => 'sub_settings', 'values' => array_merge(json_decode($component['data'], True), json_decode($component['attributes'], True))]),
            NULL,
            $component['id']
        ];

        $this -> inserter($prefill);

    }

    private function insert_individual_setters() {
        echo "Haii";


    }

    private function inserter($prefill) {
        global $db;

        $statement = $db -> prepare("INSERT INTO component_settings (type, ref, affiliation, data, attributes, corr_page, corr_element) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement -> execute($prefill);

    }

}

new Updates();


        // $type = (function ($component) {

        //     $res = [
        //         'data' => [],
        //         'attributes' => []
        //     ];

        //     foreach (json_decode($component['data'], True) as $data) {
        //         if (strlen($data) > 60) {
        //             // Vytvoř textarea form
        //             echo 'Textarea';
        //             continue;
        //         }
        //         echo 'Text_input';
        //     }

        //     foreach(json_decode($component['attributes'], True) as $attribute) {
        //         echo $attribute;
        //     } 

        // })($component);
