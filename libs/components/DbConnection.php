<?php
namespace libs\components;

use libs\helpers\ConfigHelper;
use libs\helpers\FileHelper;

/**
 * Class DbConnection
 *
 * @author Yuriy Stos
 */
class DbConnection
{
    /**
     * @var \PDO - Connection to the DB
     */
    private $connection;

    /**
     * @var DbConnection - Current class instance
     */
    private static $instance;

    /**
     * @param bool $copyToLocalStorage - Copy file to local storage or not
     *
     * DbConnection constructor.
     */
    private function __construct($copyToLocalStorage = true)
    {
        $sourceDbFilePath = ConfigHelper::getDBConnectionSettings();

        if ($copyToLocalStorage) {
            $destinationDbFilePath =  __DIR__ . '/../../tmp/db.sqlite';
            FileHelper::copyFile($sourceDbFilePath, $destinationDbFilePath);
        } else {
            $destinationDbFilePath = $sourceDbFilePath;
        }

        $this->connection = new \PDO('sqlite:' . $destinationDbFilePath);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Get DbConnection instance
     *
     * @param bool $copyToLocalStorage - Copy file to local storage or not
     * @return DbConnection
     */
    public static function getInstance($copyToLocalStorage = true)
    {
        if (!self::$instance) {
            self::$instance = new self($copyToLocalStorage);
        }
        return self::$instance;
    }

    /**
     * @return \PDO - Connection to the database
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Attach db to the default
     *
     * @param string $dbPath -  Attached DB path
     * @param string $alias -  Attached DB alias
     */
    public function attach($dbPath, $alias)
    {
        self::getInstance()->getConnection()->exec("ATTACH '$dbPath' AS $alias");
    }
}