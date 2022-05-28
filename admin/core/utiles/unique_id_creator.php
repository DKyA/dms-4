<?php

class Ids{

    public static $id = 0;

    public static function unique_id($base) {
        Ids::$id ++;
        return $base . '-' . Ids::$id;
    }

}
// Utile pomocník na zajišťování unikátních tříd...
