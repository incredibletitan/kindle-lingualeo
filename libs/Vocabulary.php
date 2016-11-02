<?php

/**
 * Class Vocabulary
 *
 * @author Yuriy Stos
 */
class Vocabulary
{
    /**
     * Get vocabulary objects
     *
     * @return array - Array with std objects with 'stem' and 'lookup' fields
     */
    public function getVocabulary()
    {
        $connection = DbConnection::getInstance()->getConnection();

        $query = <<< SQL
SELECT `w`.`stem`, `l`."usage" FROM `WORDS` `w`
  JOIN LOOKUPS `l`
    ON `w`.id=`l`.word_key
SQL;
        $smt = $connection->prepare($query);
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_OBJ);
    }
}