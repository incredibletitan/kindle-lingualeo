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
        $this->dbConnection =  DbConnection::getInstance()->getConnection();
        $this->sync();
    }

    /**
     * Get vocabulary objects
     *
     * @return array - Array with std objects with 'stem' and 'usage' fields
     */
    public function getVocabulary()
    {
        $query = <<< SQL
SELECT `w`.`stem` FROM `WORDS` `w`
  JOIN LOOKUPS `l`
    ON `w`.id=`l`.word_key
SQL;
        $smt = $this->dbConnection->prepare($query);
        $smt->execute();
        return $smt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Sync with project DB with Kindle DB
     *
     * @throws \Exception
     */
    private function sync()
    {
        if (!($sourceDbFilePath = ConfigHelper::getDBConnectionSettings())) {
            throw new \Exception('path_to_vocabulary is empty');
        }
        DbConnection::getInstance()->attach($sourceDbFilePath, 'kindle');

        /**
         * Synchronize lookups
         */
        $syncLookupsQuery = <<<SQL
      INSERT OR IGNORE INTO LOOKUPS(`id`, `word_key`, `usage`, `timestamp`)
      SELECT `id`, `word_key`, `usage`, `timestamp` FROM kindle.`LOOKUPS`;
SQL;
        $this->dbConnection->exec($syncLookupsQuery);

        /**
         * Synchronize words
         */
        $syncWordsQuery = <<<SQL
      INSERT OR IGNORE INTO WORDS(`id`, `word`, `stem`, `lang`, `category`, `timestamp`)
      SELECT `id`, `word`, `stem`, `lang`, `category`, `timestamp` FROM kindle.`WORDS`;
SQL;
        $this->dbConnection->exec($syncWordsQuery);
    }
}