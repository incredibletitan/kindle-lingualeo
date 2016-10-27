<?php

/**
 * Class Vocabulary
 *
 * @author Yuriy Stos
 */
class Vocabulary
{
    public function getVocabulary()
    {
        $connection = DbConnection::getInstance()->getConnection();
        $query = "SELECT `w`.`stem` FROM `WORDS` `w`";
        $smt = $connection->prepare($query);
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_OBJ);
    }
}