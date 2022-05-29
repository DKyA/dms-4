<?php

class StaticFunctions {

    public static function binary_search($data, $value) {

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

}
