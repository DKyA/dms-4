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

    private $accordion_id;

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

        // Obtaining accordion id
        $statement = $db -> prepare("SELECT id FROM component_list WHERE config = ?");
        $statement -> execute(['accordion']);
        $this -> accordion_id = $statement -> fetch(PDO::FETCH_COLUMN);

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

        // Seznam komponent:
        $statement = $db -> prepare("SELECT A.* FROM components A WHERE A.page IN (SELECT B.in_settings FROM pages B WHERE B.id = A.page)");
        $statement -> execute();

        $this -> components = [];

        foreach($statement as $value) {

            array_push($this -> components, $value);

            if(StaticFunctions::binary_search($this -> settings_corr_element, $value['id'])) continue;

            $this -> update_components($value);

        }

        $this -> check_and_set_individuals();

    }


    public function update_page_accordions($page) {
        global $db;

        $statement = $db -> prepare("DELETE FROM component_settings WHERE corr_page = ?");
        $statement -> execute([$page['id']]);

        $prefill = [
            $this -> accordion_id,
            StaticFunctions::Dynamic() -> d_ids("{$page['ref']}-setup", "component_settings"),
            NULL,
            json_encode(["Stránka {$page['name']}"]),
            json_encode(['type' => 'settings', 'link' => "/admin/categories/{$page['ref']}/", 'title' => 'Podívat se na stránku']),
            $page['id'],
            NULL
        ];

        $this -> inserter($prefill);

    }


    private function update_components($component) {
        global $db;
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

        // Prepare link:
        $statement = $db -> prepare("SELECT ref FROM pages WHERE autoplace != 0 AND id = EXISTS(SELECT B.page FROM components B WHERE B.ref = ?)");
        $statement -> execute([$component['ref']]);

        $link = "/admin/categories/{$statement -> fetch(PDO::FETCH_COLUMN)}/#{$component['ref']}";


        // First inserting a form::
            // Everything will have
                // a delete last / all
                // Save
                // Add new one
                // ...

        $data = ["Komponenta {$name}"];
        $attributes = ['type' => 'sub_settings', 'link' => $link, 'title' => 'Podívat se na komponentu'];
        $inserted_id = $this -> insert_new_element($affiliation, 'accordion', $component, $data, $attributes, 'setup');

        $this -> insert_new_element($inserted_id, 'utile-form', $component, [], ['type' => 'sub-settings'], 's-form');

    }

    private function check_and_set_individuals() {
        global $db;

        // SECTION WITH REAL SETTINGS:
        foreach ($this -> components as $component) {

            $c_data = json_decode($component['data'], true);
            $c_attr = json_decode($component['attributes'], true);
            $real = ($c_data == null) ? 0 : count($c_data);
            $real += ($c_attr == null) ? 0 : count($c_attr);

            // Tyhle čísla porovnat s hodnotami v tabulce.
            // A: Nenajdu match: vytvořím novou
            $statement = $db -> prepare("SELECT id FROM component_settings WHERE affiliation IN(SELECT id FROM component_settings WHERE corr_element = ? AND type = ?)");
            $statement -> execute([$component['id'], $this -> accordion_id]);

            $a_id = $statement -> fetch(PDO::FETCH_COLUMN); // accordion id

            // Getting no of children:
            $statement = $db -> prepare("SELECT COUNT(*) FROM component_settings WHERE component_settings.affiliation = ? AND component_settings.type = EXISTS (SELECT * FROM component_list WHERE component_list.config = 'input')");
            $statement -> execute([$a_id]);

            $length = $statement -> fetch(PDO::FETCH_COLUMN);

            // Compare fetch and real
            // Default: OK

            // A: Detekce nulové délky
            if ($length === 0) {
                $this -> create_new_confs($component, $c_data, $c_attr, $a_id);
                continue;
            }

            // B: Špatně čísla: Regenerace
            if ($length != $real && $length != 0) {
                $this -> update_confs($component, $c_data, $c_attr, $a_id);
            }

            // C: Všechno OK: Zkontroluji typy.
            $this -> update_types($component, $c_data, $c_attr, $a_id);

        }

    }

    private function update_types($component, $c_data, $c_attr, $a_id) {
        global $db;

        $types = $this -> determine_types($c_data, $c_attr);


        // Querying all types:
        $statement = $db -> prepare("SELECT attributes FROM component_settings WHERE affiliation = ? AND type = EXISTS(SELECT B.id FROM component_list B WHERE B.config = 'input')");
        $statement -> execute([$a_id]);

        $i = 0;
        $j = 0;
        while ($t = $statement -> fetch(PDO::FETCH_COLUMN)) {

            $t = json_decode($t, true);

            if (is_numeric($t['correspondence'])) {
                if ($t['type'] != $types['d'][$i]) {
                    $this -> update_confs($component, $c_data, $c_attr, $a_id);
                    $i++;
                }
                continue;
            }
            if ($t['type'] != $types['a'][$j]) {
                $this -> update_confs($component, $c_data, $c_attr, $a_id);
                $j++;
                continue;
            }

        }

    }

    private function update_confs($component, $c_data, $c_attr, $a_id) {
        global $db;
        // Russian method. Regenerating.

        $statement = $db -> prepare("DELETE FROM component_settings WHERE affiliation = ?");
        $statement -> execute([$a_id]);

        $this -> create_new_confs($component, $c_data, $c_attr, $a_id);

    }


    private function create_new_confs($component, $c_data, $c_attr, $a_id) {
        global $db;
        // Determine types:
        $types = $this -> determine_types($c_data, $c_attr);


        // Data::
        if ($types['d']) {
            $this -> insert_new_element($a_id, 'headline', $component, ['Textace'], ['level' => 2, 'type' => 'setting-2'], 'separator');

            for ($i = 0; $i < count($types['d']); $i++) {
                $this -> insert_new_element($a_id, 'input', $component, [], ['type' => $types['d'][$i], 'value' => array_values($c_data)[$i], 'correspondence' => array_keys($c_data)[$i], 'follows' => $component['ref']], 'conf');
            }
        }

        // Separating headline
        if ($types['a']) {
            $this -> insert_new_element($a_id, 'headline', $component, ['Funkční atributy'], ['level' => 2, 'type' => 'setting-2'], 'separator');

            for ($i = 0; $i < count($types['a']); $i++) {
                $this -> insert_new_element($a_id, 'input', $component, [], ['type' => $types['a'][$i], 'value' => array_values($c_attr)[$i], 'correspondence' => array_keys($c_attr)[$i], 'follows' => $component['ref']], 'conf');
            }
        }
    }


        /**
     * 
     * 
     * Inserts new element from given information
     * @param int $a_id Affiliation;
     * @param string $h_id Config komponenty podle tabulky component_list;
     * @param array $component Daná komponenta;
     * @param array $data Textové hodnoty;
     * @param array $attributes Atributy k použití;
     * @param string $type Typ komponenty, použit pro generaci ref;
     * 
     */

    private function insert_new_element($a_id, $h_id, $component, $data, $attributes, $type): string {
        global $db;

        // Input id:
        $statement = $db -> prepare("SELECT id FROM component_list WHERE config = ?");
        $statement -> execute([$h_id]);
        $component_id = $statement -> fetch(PDO::FETCH_COLUMN);

        // Headline id:

        $dataset = [
            $a_id,
            $component_id,
            StaticFunctions::Dynamic() -> d_ids("{$component['ref']}-$type", 'component_settings'),
            json_encode($data),
            json_encode($attributes),
            $component['id']
        ];

        $statement = $db -> prepare("INSERT INTO component_settings (affiliation, type, ref, data, attributes, corr_element) VALUES (?, ?, ?, ?, ?, ?)");
        $statement -> execute($dataset);

        return $db -> lastInsertId();

    }


    private function determine_types($data, $attr) {

        $res = [
            'd' => [],
            'a' => []
        ];

        if ($data) {

            foreach($data as $d) {
                if (strlen($d) > 64) {
                    array_push($res['d'], 'textarea');
                    continue;
                }
                array_push($res['d'], 'text');
            }

        }

        if ($attr) {
            foreach($attr as $a) {
                if (filter_var($a, FILTER_VALIDATE_INT)) {
                    array_push($res['a'], 'number');
                    continue;
                }
                if (is_string($a) && strlen($a) > 64) {
                    array_push($res['a'], 'textarea');
                    continue;
                }
                if (is_string($a) && strlen($a) < 64) {
                    array_push($res['a'], 'text');
                    continue;
                }
                // V Bucoudnu možná obrázky.
                die("Pro daný datový typ $a není vytvořený konfigurátor...");
            }

        }

        return $res;

    }

    private function inserter($prefill) {
        global $db;

        $statement = $db -> prepare(
            "INSERT INTO
            component_settings
            (type, ref, affiliation, data, attributes, corr_page, corr_element)
            VALUES
            (?, ?, ?, ?, ?, ?, ?)");
        $statement -> execute($prefill);

    }

}

// global $db;
$updates = new Updates();
