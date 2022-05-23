<?php

class PAGE_INFO {

    public $info;

    public $id;
    public $ref;
    public $name;
    public $in_nav;
    public $infobar;
    public $in_settings;
    public $special;

    public function __construct($ref) {
        global $db;
        $statement = $db -> prepare("SELECT * FROM pages WHERE ref = ?");
        $statement -> execute([$ref]);

        $this -> info = $statement -> fetch(PDO::FETCH_DEFAULT);
        $this -> initialise();
    }

    private function initialise() {
        $this -> id = $this -> info['id'];
        $this -> ref = $this -> info['ref'];
        $this -> name = $this -> info['name'];
        $this -> in_nav = $this -> info['in_nav'];
        $this -> infobar = $this -> info['infobar'];
        $this -> in_settings = $this -> info['in_settings'];
        $this -> special = $this -> info['special'];
    }

}

$PI = new PAGE_INFO($reference);
