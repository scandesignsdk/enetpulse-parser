<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Statement;
use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Utils\BetweenDate;
use SDM\Enetpulse\Utils\Utils;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @codeCoverageIgnore
     *
     * @throws DBALException
     *
     * @return QueryBuilder
     */
    protected function getBuilder(): QueryBuilder
    {
        try {
            return $this->configuration->getConnection()->createQueryBuilder();
        } catch (DBALException $exception) {
            throw $exception;
        }
    }

    /**
     * @param QueryBuilder $builder
     *
     * @return \stdClass[]
     */
    protected function fetchObjects(QueryBuilder $builder): array
    {
        /** @var Statement $result */
        $result = $builder->execute();

        return $result->fetchAll(FetchMode::STANDARD_OBJECT);
    }

    /**
     * @param QueryBuilder $builder
     *
     * @return \stdClass|null
     */
    protected function fetchSingle(QueryBuilder $builder): ?\stdClass
    {
        /** @var Statement $result */
        $result = $builder->execute();
        $fetch = $result->fetch(FetchMode::STANDARD_OBJECT);

        return $fetch ?: null;
    }

    protected function createDate(string $date): \DateTime
    {
        return new \DateTime($date);
    }

    protected function setDateHigherThanToday(QueryBuilder $qb, string $field): void
    {
        $qb->andWhere(sprintf(
            '(DATE_FORMAT(%s, "%%Y-%%m-%%d") > "%s")',
            $field,
            Utils::getToday()->format('Y-m-d')
        ));
    }

    protected function setDateBetweenStartEndField(QueryBuilder $qb, string $startField, string $endField, ?\DateTime $date = null): void
    {
        if (!$date) {
            $date = Utils::getToday();
        }

        $qb->andWhere(sprintf(
            '("%s" BETWEEN %s AND %s)',
            $date->format('Y-m-d 00:00:00'),
            $startField,
            $endField
        ));
    }

    protected function setDateFieldBetweenDate(QueryBuilder $qb, string $dateField, BetweenDate $betweenDate): void
    {
        $qb->andWhere(sprintf(
            '(DATE_FORMAT(%s, "%%Y-%%m-%%d %%H:%%i:%%s") BETWEEN "%s" AND "%s")',
            $dateField,
            $betweenDate->getFromDate()->format('Y-m-d H:i:s'),
            $betweenDate->getToDate()->format('Y-m-d H:i:s')
        ));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $tables
     */
    protected function removeDeleted(QueryBuilder $queryBuilder, array $tables): void
    {
        foreach ($tables as $table) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq($table . '.del', '"no"'));
        }
    }
}
