<?php
namespace libs\models;

use libs\components\DbConnection;
use libs\helpers\ConfigHelper;

/**
 * Class Vocabulary
 *
 * @author Yuriy Stos
 */
class Vocabulary
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * Vocabulary constructor.
     */
    public function __construct()
    {
        $dbInstance = DbConnection::getInstance();

        if ($sourceDbFilePath = ConfigHelper::getDBConnectionSettings()) {
            $dbInstance->attach($sourceDbFilePath, 'kindle');
        }
        $this->dbConnection = $dbInstance->getConnection();
    }

    /**
     * Get vocabulary objects
     *
     * @return array - Array with std objects with 'stem' and 'usage' fields
     */
    public function getVocabulary()
    {
        $query = <<< SQL
SELECT `w`.`stem`, `l`."usage" FROM kindle.`WORDS` `w`
  JOIN LOOKUPS `l`
    ON `w`.id=`l`.word_key
SQL;
        $smt = $this->dbConnection->prepare($query);
        $smt->execute();
        return $smt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function sync()
    {

    }
}