<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Odds;

class EventOddsProvider extends AbstractOddsProvider
{
    /**
     * @param Event $event
     *
     * @return array|Odds[]
     */
    public function getOddsByEvent(Event $event): array
    {
        return $this->getOddsByEventId($event->getId());
    }

    /**
     * @param int $eventId
     *
     * @return Odds[]
     */
    public function getOddsByEventId(int $eventId): array
    {
        return $this->build($this->queryBuilder($eventId));
    }

    protected function queryBuilder(int $eventId): QueryBuilder
    {
        $qb = $this->makeQueryBuilder();
        $qb->andWhere(
            $qb->expr()->eq('o.objectFK', ':eventId'),
            $qb->expr()->eq('o.object', ':object')
        );
        $qb->setParameter(':eventId', $eventId);
        $qb->setParameter(':object', 'event');

        $qb->andWhere(
            $qb->expr()->in('o.type', ['"1x2"', '"oe"', '"ou"'])
        );

        return $qb;
    }
}
