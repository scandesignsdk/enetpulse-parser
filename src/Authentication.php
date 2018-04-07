<?php

namespace SDM\Enetpulse;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Authentication
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Database DSN.
     *
     * @example mysql://username:password@host:3306/databasename
     *
     * @param string $dsn
     */
    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if (null === $this->connection) {
            $config = new Configuration();
            $connectionParams = [
                'url' => $this->dsn,
            ];

            return $this->connection = DriverManager::getConnection($connectionParams, $config);
        }

        return $this->connection;
    }
}
