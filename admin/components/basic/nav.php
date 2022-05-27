<?php
// Tady se připraví properties, zbytek se bude dít v HTML věci.

class Nav {

    public $links;
    private $db;

    function __construct() {
        global $db;
        $this -> db = $db;

        $this -> get_links();
        $this -> open_html_page();

    }

    private function get_links() {
        $this -> links = [];

        $statement = $this -> db -> prepare("SELECT ref, name FROM pages WHERE in_nav >= 1 AND autoplace = 1");
        $statement -> execute();

        foreach ($statement as $row) {
            array_push($this -> links, new Link($row));
        }

    }


    private function open_html_page() {
        global $path, $text, $PI;
        require $path['html'] . 'components/nav.html';
    }

    private function print_links() {
        // Použita v komponentě.

        foreach ($this -> links as $link) {

            ?>

<a class="c-nav__link <?=$link->active?>" href="/admin/categories/<?=$link->ref?>"><li><?=$link->name?></li></a>

            <?php

        }

    }

}

class Link {

    public $ref;
    public $name;
    public $active;

    function __construct($data) {
        // Tohle je tady připravené pro jednodušší implementaci různých user-tříd,...
        // Chci jednotlivým odkazům dávat různé atributy podle toho, jestli jsou aktivní, nebo třeba nedostupné.

        global $PI;

        $this -> ref = $data['ref'];
        $this -> name = $data['name'];
        $this -> active = '';

        if ($data['ref'] == $PI -> ref) {
            $this -> active = 'c-nav__link--active';
        }

    }

}

$nav = new Nav;