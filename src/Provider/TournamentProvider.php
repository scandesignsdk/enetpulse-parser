<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Model\Tournament;

class TournamentProvider extends AbstractProvider
{
    /**
     * @var Tournament[]
     */
    private static $tournaments = [];

    /**
     * @param bool  $onlyActive
     * @param Sport $sport
     *
     * @return Tournament[]
     */
    public function getTournaments(Sport $sport = null, bool $onlyActive = true): array
    {
        $qb = $this->queryBuilder($onlyActive);
        if ($sport) {
            $qb->andWhere(
                $qb->expr()->eq('tt.sportFk', ':sport')
            );
            $qb->setParameter(':sport', $sport->getId());
        }

        return $this->build($qb);
    }

    /**
     * @param int  $templateId
     * @param bool $onlyActive
     *
     * @return Tournament[]
     */
    public function getTournamentsByTemplate(int $templateId, bool $onlyActive = true): array
    {
        $qb = $this->queryBuilder($onlyActive);
        $qb->andWhere(
            $qb->expr()->eq('tt.id', ':templateId')
        );
        $qb->setParameter(':templateId', $templateId);

        return $this->build($qb);
    }

    /**
     * @param string     $country
     * @param Sport|null $sport
     * @param bool       $onlyActive
     *
     * @return Tournament[]
     */
    public function getTournamentsByCountry(string $country, Sport $sport = null, bool $onlyActive = true): array
    {
        $qb = $this->queryBuilder($onlyActive);
        $qb->andWhere(
            $qb->expr()->eq('country.name', ':country')
        );
        $qb->setParameter(':country', $country);

        if ($sport) {
            $qb->andWhere(
                $qb->expr()->eq('tt.sportFk', ':sport')
            );
            $qb->setParameter(':sport', $sport->getId());
        }

        return $this->build($qb);
    }

    public function getTournamentByStageId(int $tournamentStageId): ?Tournament
    {
        $qb = $this->queryBuilder(false);
        $qb->andWhere(
            $qb->expr()->eq('ts.id', ':stageId')
        );
        $qb->setParameter(':stageId', $tournamentStageId);

        return $this->buildSingle($qb);
    }

    public function getTournamentByTournamentId(int $tournamentId): ?Tournament
    {
        $qb = $this->queryBuilder(false);
        $qb->andWhere(
            $qb->expr()->eq('t.id', ':seasonId')
        );
        $qb->setParameter(':seasonId', $tournamentId);

        return $this->buildSingle($qb);
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Tournament[]
     */
    private function build(QueryBuilder $qb): array
    {
        foreach ($this->fetchObjects($qb) as $item) {
            $this->createObject($item);
        }
        $seasons = self::$tournaments;
        self::$tournaments = [];

        return array_values($seasons);
    }

    private function buildSingle(QueryBuilder $qb): ?Tournament
    {
        foreach ($this->fetchObjects($qb) as $item) {
            $this->createObject($item);
        }

        $seasons = self::$tournaments;
        self::$tournaments = [];
        if (0 === \count($seasons)) {
            return null;
        }

        return array_pop($seasons);
    }

    private function createObject(\stdClass $object): void
    {
        $id = $object->t_id;
        if (isset(self::$tournaments[$id])) {
            $tournament = self::$tournaments[$id];
        } else {
            $tournament = new Tournament(
                (int) $object->t_id,
                $object->t_name,
                new Tournament\TournamentTemplate(
                    (int) $object->tt_id,
                    $object->tt_name,
                    new Sport(
                        (int) $object->sport_id,
                        $object->sport_name
                    ),
                    $object->tt_gender
                )
            );
        }

        $tournament->addStage(new Tournament\TournamentStage(
            (int) $object->ts_id,
            $object->ts_name,
            $object->country_name,
            $this->createDate($object->ts_startdate),
            $this->createDate($object->ts_enddate),
            $tournament
        ));

        self::$tournaments[$id] = $tournament;
    }

    protected function queryBuilder(bool $onlyActive): QueryBuilder
    {
        $qb = $this->getBuilder();
        // Tournament template
        $qb
            // Tournament Template
            ->from('tournament_template', 'tt')
            ->addSelect('tt.id as tt_id', 'tt.name as tt_name', 'tt.gender as tt_gender')
            // Tournament
            ->innerJoin('tt', 'tournament', 't', 'tt.id = t.tournament_templateFK')
            ->addSelect('t.id as t_id', 't.name as t_name')
            // Tournament stage
            ->innerJoin('t', 'tournament_stage', 'ts', 't.id = ts.tournamentFK')
            ->addSelect('ts.id as ts_id', 'ts.name as ts_name', 'ts.gender as ts_gender', 'ts.startdate as ts_startdate', 'ts.enddate as ts_enddate')
            // Country
            ->innerJoin('ts', 'country', 'country', 'ts.countryFK = country.id')
            ->addSelect('country.id as country_id', 'country.name as country_name')
            // Sport
            ->innerJoin('tt', 'sport', 'sport', 'tt.sportFK = sport.id')
            ->addSelect('sport.id as sport_id', 'sport.name as sport_name')
        ;
        $this->removeDeleted($qb, ['ts', 'tt', 'country', 'sport']);

        if ($onlyActive) {
            $this->setDateBetweenStartEndField($qb, 'ts.startdate', 'ts.enddate');
        }

        if ($tournamentTemplates = $this->configuration->getTournamentTemplates()) {
            $qb->andWhere($qb->expr()->in('tt.id', $tournamentTemplates));
        }

        if ($sports = $this->configuration->getSports()) {
            $qb->andWhere($qb->expr()->in('sport.id', $sports));
        }

        $qb
            ->addOrderBy('t.ut', 'DESC')
            ->addOrderBy('ts.startdate', 'DESC')
        ;

        return $qb;
    }
}
