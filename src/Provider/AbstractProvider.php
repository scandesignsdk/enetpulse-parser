<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Statement;
use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Utils\BetweenDate;

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
     * @return QueryBuilder
     */
    protected function getBuilder(): QueryBuilder
    {
        try {
            return $this->configuration->getConnection()->createQueryBuilder();
        } catch (DBALException $exception) {
            echo $exception->getMessage();
            exit;
        }
    }

    protected function debugQuery(QueryBuilder $builder): void
    {
        dump($builder->getSQL(), $builder->getParameters());
        exit;
    }

    /**
     * @param QueryBuilder $builder
     *
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return \stdClass
     */
    protected function fetchSingle(QueryBuilder $builder): \stdClass
    {
        /** @var Statement $result */
        $result = $builder->execute();

        return $result->fetch(FetchMode::STANDARD_OBJECT);
    }

    protected function createDate(string $date): \DateTime
    {
        return new \DateTime($date);
    }

    protected function setDateBetweenStartEndField(QueryBuilder $qb, string $startField, string $endField, ?\DateTime $date = null): void
    {
        if (!$date) {
            $date = new \DateTime();
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
            '(DATE_FORMAT(%s, "%%Y-%%m-%%d") BETWEEN %s AND %s)',
            $dateField,
            $betweenDate->getFromDate()->format('Y-m-d'),
            $betweenDate->getToDate()->format('Y-m-d')
        ));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $tables
     */
    protected function removeDeleted(QueryBuilder $queryBuilder, array $tables): void
    {
        foreach ($tables as $table) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq($table.'.del', ':no_'.$table));
            $queryBuilder->setParameter(':no_'.$table, 'no');
        }
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    protected function createBool($item): bool
    {
        if (\is_bool($item)) {
            return $item;
        }

        if (\is_string($item)) {
            return \in_array(mb_strtolower($item), ['yes', 'y', '1'], true);
        }

        if (\is_int($item)) {
            return 0 !== $item;
        }

        return filter_var($item, FILTER_VALIDATE_BOOLEAN);
    }
}
