<?php

namespace SDM\Enetpulse;

use Doctrine\DBAL\Configuration as DBALConfiguration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Configuration
{
    /**
     * @var string
     */
    private $databaseDsn;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var int[]
     */
    private $oddsProviders = [];

    /**
     * @var int[]
     */
    private $tournamentTemplates = [];

    /**
     * @param string $databaseDsn mysql://username:password@host:3306/databasename
     */
    public function __construct(string $databaseDsn)
    {
        $this->databaseDsn = $databaseDsn;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if (null === $this->connection) {
            $config = new DBALConfiguration();
            $connectionParams = [
                'url' => $this->databaseDsn,
            ];

            return $this->connection = DriverManager::getConnection($connectionParams, $config);
        }

        return $this->connection;
    }

    /**
     * Set which odds providers that should be used.
     *
     * @param int[] $providers
     *
     * @return Configuration
     */
    public function setOddsProviders(array $providers = []): self
    {
        $this->oddsProviders = $providers;

        return $this;
    }

    /**
     * Set which tournament templates that should be viewed.
     *
     * @param int[] $tournamentTemplates
     *
     * @return Configuration
     */
    public function setTournamentTemplates(array $tournamentTemplates = []): self
    {
        $this->tournamentTemplates = $tournamentTemplates;

        return $this;
    }

    /**
     * Which odds providers should be viewed.
     *
     * @return int[]
     */
    public function getOddsProviders(): array
    {
        return $this->oddsProviders;
    }

    /**
     * Which tournament templates should be viewed.
     *
     * @return int[]
     */
    public function getTournamentTemplates(): array
    {
        return $this->tournamentTemplates;
    }
}
