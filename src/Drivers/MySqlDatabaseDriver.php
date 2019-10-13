<?php

namespace Nerbiz\PrivateStats\Drivers;

use PDOStatement;

class MySqlDatabaseDriver extends AbstractDatabaseDriver
{
    /**
     * {@inheritdoc}
     */
    public function ensureTable(): void
    {
        $this->connection->exec(sprintf(
            'create table if not exists `%s` (
                `id` int(10) unsigned not null auto_increment,
                `timestamp` int(10) unsigned not null,
                %s,
                %s,
                %s,
                primary key (`id`)
            )',
            $this->tableName,
            $this->getColumnDefinition('ip_hash'),
            $this->getColumnDefinition('url'),
            $this->getColumnDefinition('referrer')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function ensureColumns(): void
    {
        $statement = $this->connection->query(sprintf(
            'show columns
            from `%s`',
            $this->tableName
        ));

        $currentColumns = array_map(function ($row) {
            return $row->Field;
        }, $statement->fetchAll());

        $missingColumns = array_diff($this->requiredColumns, $currentColumns);

        // Add any missing columns
        foreach ($missingColumns as $columnName) {
            $this->connection->exec(sprintf(
                'alter table `%s`
                add column %s',
                $this->tableName,
                $this->getColumnDefinition($columnName)
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPreparedInsertStatement(): PDOStatement
    {
        $columns = [];
        $placeholders = [];

        // Create the column and placeholder values
        foreach ($this->requiredColumns as $columnName) {
            $columns[] = '`' . $columnName . '`';
            $placeholders[] = ':' . $columnName;
        }

        return $this->connection->prepare(sprintf(
            'insert into `%s`
            (%s)
            values(%s)',
            $this->tableName,
            implode(', ', $columns),
            implode(', ', $placeholders)
        ));
    }

    /**
     * Get a column definition for creating or altering a table
     * @param string $columnName
     * @return string
     */
    protected function getColumnDefinition(string $columnName): string
    {
        switch ($columnName) {
            case 'ip_hash':
                return '`ip_hash` varchar(191) null';
            case 'url':
                return '`url` varchar(191) null';
            case 'referrer':
                return '`referrer` varchar(191) null';
            default:
                return '';
        }
    }
}
