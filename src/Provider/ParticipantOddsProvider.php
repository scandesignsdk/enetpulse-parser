<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Odds;

class ParticipantOddsProvider extends AbstractOddsProvider
{
    /**
     * @param int $participantId
     *
     * @return Odds[]
     */
    public function getOddsByEventParticipantId(int $participantId): array
    {
        $qb = $this->queryBuilder($participantId);

        return $this->build($qb);
    }

    protected function queryBuilder(int $participantId): QueryBuilder
    {
        $qb = $this->makeQueryBuilder();

        $qb->andWhere($qb->expr()->eq('o.iparam', ':participantId'));
        $qb->setParameter(':participantId', $participantId);

        return $qb;
    }
}
