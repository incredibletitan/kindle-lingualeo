<?php
$config = require 'config.php';

try {
    $dbh = new PDO('sqlite:' . $config['path_to_vocabulary']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query =  "SELECT `w`.`stem` FROM `WORDS` `w`";
    $smt = $dbh->prepare($query);
    $smt->execute();
    $obj = $smt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}