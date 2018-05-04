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

        $qb->andWhere($qb->expr()->eq('o.object', ':object'));
        $qb->andWhere($qb->expr()->eq('o.objectFK', ':eventId'));
        $qb->andWhere($qb->expr()->eq('o.type', ':otype'));
        $qb->andWhere($qb->expr()->eq('o.scope', ':oscope'));
        $qb->setParameter(':object', 'event');
        $qb->setParameter(':eventId', $eventId);
        $qb->setParameter(':otype', '1x2');
        $qb->setParameter(':oscope', 'ord');

        return $qb;
    }
}
