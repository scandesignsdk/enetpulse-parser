<?php

declare(strict_types=1);

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
    private $sports = [];

    /**
     * @var int[]
     */
    private $oddsProviders = [];

    /**
     * @var int[]
     */
    private $tournamentTemplates = [];

    /**
     * @var string[]
     */
    private $oddsCountryNames = [];

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
     * Set which sports you only want to be used
     *
     * @param array $sports
     *
     * @return Configuration
     */
    public function setSports(array $sports = []): self
    {
        $this->sports = $sports;

        return $this;
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
     * Set which odds providers country names that should be used.
     *
     * @param string[] $countryNames
     *
     * @return Configuration
     */
    public function setOddsProviderCountryNames(array $countryNames = []): self
    {
        $this->oddsCountryNames = $countryNames;

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
     * Get which sports should only be used
     *
     * @return int[]
     */
    public function getSports(): array
    {
        return $this->sports;
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

    /**
     * Which odds providers country names should be viewed.
     *
     * @return string[]
     */
    public function getOddsCountryNames(): array
    {
        return $this->oddsCountryNames;
    }
}
