<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Model\Tournament;
use SDM\Enetpulse\Utils\BetweenDate;
use SDM\Enetpulse\Utils\Utils;

class EventProvider extends AbstractProvider
{
    /**
     * @param int                          $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     * @param BetweenDate|null             $betweenDate
     *
     * @return Event[]
     */
    public function getUpcomingEvents(int $limit = 30, array $tournaments = [], array $tournamentStages = [], ?BetweenDate $betweenDate = null): array
    {
        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $betweenDate);
        $qb->andWhere($qb->expr()->eq('e.status_type', ':status'));
        $qb->setParameter(':status', 'notstarted');
        $this->setDateHigherThanToday($qb, 'e.startdate');
        $qb->addOrderBy('e.startdate', 'ASC');
        $qb->addOrderBy('e.id', 'DESC');

        return $this->createEvents($qb);
    }

    /**
     * @param int                          $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     * @param BetweenDate|null             $betweenDate
     *
     * @return Event[]
     */
    public function getFinishedEvents(int $limit = 30, array $tournaments = [], array $tournamentStages = [], ?BetweenDate $betweenDate = null): array
    {
        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $betweenDate);
        $qb->andWhere($qb->expr()->eq('e.status_type', ':status'));
        $qb->setParameter(':status', 'finished');
        $qb->addOrderBy('e.startdate', 'DESC');
        $qb->addOrderBy('e.id', 'DESC');

        return $this->createEvents($qb);
    }

    /**
     * @param int|null                     $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     * @param BetweenDate|null             $betweenDate
     *
     * @return Event[]
     */
    public function getLiveEvents(?int $limit = null, array $tournaments = [], array $tournamentStages = [], ?BetweenDate $betweenDate = null): array
    {
        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $betweenDate);
        $qb->andWhere($qb->expr()->eq('e.status_type', ':status'));
        $qb->setParameter(':status', 'inprogress');
        $qb->addOrderBy('e.startdate', 'ASC');
        $qb->addOrderBy('e.id', 'DESC');

        return $this->createEvents($qb);
    }

    /**
     * @param int                          $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     *
     * @return Event[]
     */
    public function getYesterdayEvents(int $limit = 30, array $tournaments = [], array $tournamentStages = []): array
    {
        $between = new BetweenDate(
            Utils::getToday()->sub(new \DateInterval('P1D'))->setTime(0, 0, 0),
            Utils::getToday()->sub(new \DateInterval('P1D'))->setTime(23, 59, 59)
        );

        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $between);
        $qb->addOrderBy('e.startdate', 'DESC');

        return $this->createEvents($qb);
    }

    /**
     * @param int                          $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     *
     * @return Event[]
     */
    public function getTodayEvents(int $limit = 30, array $tournaments = [], array $tournamentStages = []): array
    {
        $between = new BetweenDate(
            Utils::getToday()->setTime(0, 0, 0),
            Utils::getToday()->setTime(23, 59, 59)
        );
        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $between);
        $qb->addOrderBy('e.startdate', 'ASC');

        return $this->createEvents($qb);
    }

    /**
     * @param int                          $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $tournamentStages
     *
     * @return Event[]
     */
    public function getTomorrowEvents(int $limit = 30, array $tournaments = [], array $tournamentStages = []): array
    {
        $between = new BetweenDate(
            Utils::getToday()->add(new \DateInterval('P1D'))->setTime(0, 0, 0),
            Utils::getToday()->add(new \DateInterval('P1D'))->setTime(23, 59, 59)
        );
        $qb = $this->queryBuilder($limit, $tournaments, $tournamentStages, $between);
        $qb->addOrderBy('e.startdate', 'ASC');

        return $this->createEvents($qb);
    }

    public function getEvent(int $eventId): ?Event
    {
        $qb = $this->queryBuilder(null);
        $qb->andWhere($qb->expr()->eq('e.id', ':eventId'));
        $qb->setParameter(':eventId', $eventId);

        if ($result = $this->fetchSingle($qb)) {
            return $this->createObject($result);
        }

        return null;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Event[]
     */
    private function createEvents(QueryBuilder $qb): array
    {
        $events = [];
        foreach ($this->fetchObjects($qb) as $item) {
            $events[] = $this->createObject($item);
        }

        return $events;
    }

    private function createObject(\stdClass $object): Event
    {
        $event = new Event(
            $object->e_id,
            $object->e_name,
            new Tournament\TournamentStage(
                $object->ts_id,
                $object->ts_name,
                $object->country_name,
                $this->createDate($object->ts_startdate),
                $this->createDate($object->ts_enddate),
                new Tournament(
                    $object->t_id,
                    $object->t_name,
                    new Tournament\TournamentTemplate(
                        $object->tt_id,
                        $object->tt_name,
                        new Sport(
                            $object->sport_id,
                            $object->sport_name
                        ),
                        $object->tt_gender
                    )
                )
            ),
            $this->createDate($object->e_startdate),
            $object->e_status_type
        );
        $event->setParticipants((new ParticipantProvider($this->configuration))->getParticipantsFromEvent($event));

        return $event;
    }

    /**
     * @param int|null                     $limit
     * @param Tournament[]                 $tournaments
     * @param Tournament\TournamentStage[] $stages
     * @param BetweenDate|null             $betweenDate
     *
     * @return QueryBuilder
     */
    protected function queryBuilder(?int $limit, array $tournaments = [], array $stages = [], ?BetweenDate $betweenDate = null): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            // Event
            ->from('`event`', 'e')
            ->addSelect('e.id as e_id', 'e.name as e_name', 'e.startdate as e_startdate', 'e.status_type as e_status_type')
            // Tournament stage
            ->innerJoin('e', 'tournament_stage', 'ts', 'e.tournament_stageFK = ts.id')
            ->addSelect('ts.id as ts_id', 'ts.name as ts_name', 'ts.gender as ts_gender', 'ts.startdate as ts_startdate', 'ts.enddate as ts_enddate')
            // Country
            ->innerJoin('ts', 'country', 'country', 'ts.countryFK = country.id')
            ->addSelect('country.id as country_id', 'country.name as country_name')
            // Tournament
            ->innerJoin('ts', 'tournament', 't', 'ts.tournamentFK = t.id')
            ->addSelect('t.id as t_id', 't.name as t_name')
            // Tournament Template
            ->innerJoin('t', 'tournament_template', 'tt', 't.tournament_templateFK = tt.id')
            ->addSelect('tt.id as tt_id', 'tt.name as tt_name', 'tt.gender as tt_gender')
            // Sport
            ->innerJoin('tt', 'sport', 'sport', 'tt.sportFK = sport.id')
            ->addSelect('sport.id as sport_id', 'sport.name as sport_name')
        ;
        $this->removeDeleted($qb, ['e', 'ts', 't', 'tt', 'sport', 'country']);

        if ($tournaments) {
            $qb->andWhere($qb->expr()->in('t.id', array_map(function (Tournament $tournament) { return $tournament->getId(); }, $tournaments)));
        }

        if ($stages) {
            $qb->andWhere($qb->expr()->in('ts.id', array_map(function (Tournament\TournamentStage $stage) { return $stage->getId(); }, $stages)));
        }

        if ($betweenDate) {
            $this->setDateFieldBetweenDate($qb, 'e.startdate', $betweenDate);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }
}
