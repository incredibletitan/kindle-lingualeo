<?php

/**
 * Class DbConnection
 *
 * @author Yuriy Stos
 */
class DbConnection
{
    /**
     * @var PDO - Connection to the DB
     */
    private $connection;

    /**
     * @var DbConnection - Current class instance
     */
    private static $instance;

    /**
     * DbConnection constructor.
     */
    private function __construct()
    {
        $this->connection = new PDO('sqlite:' . ConfigHelper::getDBConnectionSettings());
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Get DbConnection instance
     *
     * @return DbConnection
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return PDO - Connection to the database
     */
    public function getConnection()
    {
        return $this->connection;
    }
}