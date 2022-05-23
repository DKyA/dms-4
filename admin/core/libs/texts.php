<?php

$text = (function($db) {

    $statement = $db -> prepare("SELECT value FROM texts WHERE lang = ?");
    $statement -> execute([0]);

    $res = $statement -> fetchAll(PDO::FETCH_COLUMN);
    return $res;

})($db);
