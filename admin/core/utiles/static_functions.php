<?php

class StaticFunctions {

    public static function binary_search($data, $value) {

        if (count($data) == 0) return False;

        $pivot = floor(count($data) / 2);

        if ($data[$pivot] == $value) return True;
        if (count($data) <= 1) return False;

        // So we continue...

        if ($data[$pivot] > $value) {
            // Všechno mezi data[0] a data[$pivot]. Bez...
            return self::binary_search(array_slice($data, 0, $pivot), $value);
        }
        else {
            // data[pivot] až do konce
            return self::binary_search(array_slice($data, $pivot), $value);
        }

    }

    public static $id = 0;

    public static function ids($base) {
        StaticFunctions::$id ++;
        return $base . '-' . StaticFunctions::$id;
    }


    public function __construct() {
        
    }

    public static function Dynamic() {
        return new StaticFunctions();
    }

    public function d_ids($base, $table) {
        global $db;

        $query = "SELECT MAX(id) FROM $table";
        $statement = $db -> prepare($query);
        $statement -> execute();
        return $base . '-' . $statement -> fetch(PDO::FETCH_COLUMN)+1;

    }

}
