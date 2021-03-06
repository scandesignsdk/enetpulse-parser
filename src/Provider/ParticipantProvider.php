<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Event\Participant;
use SDM\Enetpulse\Model\Participant\Result;
use SDM\Enetpulse\Utils\Utils;

class ParticipantProvider extends AbstractProvider
{
    private static $participant = [];

    /**
     * @param Event $event
     *
     * @return Participant[]
     */
    public function getParticipantsFromEvent(Event $event): array
    {
        $qb = $this->queryBuilder($event->getId());

        return $this->build($qb, $event);
    }

    /**
     * @param QueryBuilder $qb
     * @param Event        $event
     *
     * @return Participant[]
     */
    private function build(QueryBuilder $qb, Event $event): array
    {
        foreach ($this->fetchObjects($qb) as $item) {
            $this->createObject($item, $event);
        }
        $participant = self::$participant;
        self::$participant = [];

        return array_values($participant);
    }

    private function createObject(\stdClass $object, Event $event): void
    {
        if (isset(self::$participant[$object->p_id])) {
            $p = self::$participant[$object->p_id];
        } else {
            $p = new Participant(
                (int) $object->p_id,
                $object->p_name,
                $object->p_type,
                $object->country_name,
                $this->createImage($object->i_contenttype, $object->i_value),
                null, // (new ParticipantOddsProvider($this->configuration))->getOddsByEventParticipantId((int) $object->p_id),
                [],
                $event
            );
        }

        $p->addResult(new Result($object->r_value, $object->r_result_code, $this->createDate($object->r_ut)));
        self::$participant[$object->p_id] = $p;
    }

    protected function queryBuilder(int $eventId): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            // Event participants
            ->from('event_participants', 'ep')
            ->addSelect('ep.id as ep_id')
            // Participant
            ->innerJoin('ep', 'participant', 'p', 'ep.participantFK = p.id')
            ->addSelect('p.id as p_id', 'p.name as p_name', 'p.gender as p_gender', 'p.type as p_type')
            // Country
            ->innerJoin('p', 'country', 'c', 'p.countryFK = c.id')
            ->addSelect('c.name as country_name')
            // Result
            ->leftJoin('ep', 'result', 'r', 'r.event_participantsFK = ep.id')
            ->addSelect('r.value as r_value', 'r.result_code as r_result_code', 'r.ut as r_ut')
            // Logo
            ->leftJoin('p', 'image', 'i', 'p.id = objectFK AND i.object = "participant" AND i.type = "logo"')
            ->addSelect('i.value as i_value', 'i.contenttype as i_contenttype')
        ;

        $qb
            ->andWhere($qb->expr()->eq('ep.eventFK', ':eventId'))
            ->setParameter(':eventId', $eventId)
            ->addOrderBy('ep.number', 'ASC')
        ;

        $this->removeDeleted($qb, ['ep', 'p', 'c', 'r']);

        return $qb;
    }
}
