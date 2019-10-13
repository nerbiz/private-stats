<?php

namespace Nerbiz\PrivateStats\Drivers;

use PDO;
use PDOStatement;

abstract class AbstractDatabaseDriver
{
    /**
     * The database connection
     * @var PDO
     */
    protected $connection;

    /**
     * The table name to store in and read from
     * @var string
     */
    protected $tableName;

    /**
     * @param PDO    $connection
     * @param string $tableName
     */
    public function __construct(PDO $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    /**
     * Make sure the statistics table exists
     * @return void
     */
    abstract public function ensureTable(): void;

    /**
     * Make sure the statistics table has all the required columns
     * @return void
     */
    abstract public function ensureColumns(): void;

    /**
     * Get a prepared statement for inserting statistics into a database
     * @return PDOStatement
     */
    abstract public function getPreparedInsertStatement(): PDOStatement;
}
