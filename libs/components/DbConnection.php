<?php
namespace libs\components;

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
        $sourceDbFilePath =  __DIR__ . '/../../db/db.sqlite';
        $destinationDbFilePath =  __DIR__ . '/../../tmp/db.sqlite';

        if (!file_exists($destinationDbFilePath)) {
            FileHelper::copyFile($sourceDbFilePath, $destinationDbFilePath);
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
        $this->connection->exec("ATTACH DATABASE '$dbPath' AS $alias");
    }
}