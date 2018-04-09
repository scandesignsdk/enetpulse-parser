<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Event\Participant;

class ParticipantProvider extends AbstractProvider
{
    /**
     * @param int $eventId
     *
     * @return Participant[]
     */
    public function getParticipantFromEventId(int $eventId): array
    {
        $qb = $this->queryBuilder($eventId);
        $participants = [];
        foreach ($this->fetchObjects($qb) as $item) {
            $participants[] = $this->createObject($item);
        }

        return $participants;
    }

    private function createObject(\stdClass $object): Participant
    {
        return new Participant(
            $object->p_id,
            $object->p_name,
            $object->p_type,
            $object->country_name,
            $object->r_value,
            $object->r_result_code,
            (new OddsProvider($this->configuration))->getOddsByEventParticipantId($object->p_id)
        );
    }

    protected function queryBuilder(int $eventId): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            // Event participants
            ->from('event_participants', 'ep')
            ->addSelect('ep.id as ep_id')
            // Participant
            ->innerJoin('ep', 'participants', 'p', 'ep.participantFK = p.id')
            ->addSelect('p.id as p_id', 'p.name as p_name', 'p.gender as p_gender', 'p.type as p_type')
            // Country
            ->innerJoin('p', 'country', 'c', 'p.countryFK = c.id')
            ->addSelect('c.name as country_name')
            // Result
            ->leftJoin('ep', 'result', 'r', 'r.event_participantsFK = ep.id')
            ->addSelect('r.value as r_value', 'r.result_code as r_result_code')
        ;

        $this->removeDeleted($qb, ['ep', 'p', 'c', 'r']);

        $qb
            ->andWhere($qb->expr()->eq('ep.evenFK', ':eventId'))
            ->setParameter(':eventId', $eventId)
            ->addOrderBy('ep.number', 'ASC')
        ;

        return $qb;
    }
}
