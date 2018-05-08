<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Model\Standing;
use SDM\Enetpulse\Model\Tournament;
use SDM\Enetpulse\Model\Tournament\TournamentStage;
use SDM\Enetpulse\Utils\Utils;

class StandingProvider extends AbstractProvider
{

    /**
     * @param TournamentStage $stage
     *
     * @return Standing
     */
    public function getStandingFromTournamentStage(TournamentStage $stage): Standing
    {
        return $this->getStandingFromTournamentStageId($stage->getId());
    }

    /**
     * @param int $stage
     *
     * @return Standing
     */
    public function getStandingFromTournamentStageId(int $stage): Standing
    {
        return $this->build($this->queryBuilder($stage));
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Standing
     */
    private function build(QueryBuilder $qb): Standing
    {
        return $this->createObject($this->fetchSingle($qb));
    }

    protected function createObject(\stdClass $data): Standing
    {
        $standing = new Standing(
            new TournamentStage(
                $data->ts_id,
                $data->ts_name,
                $data->country_name,
                $this->createDate($data->ts_startdate),
                $this->createDate($data->ts_enddate),
                new Tournament(
                    $data->t_id,
                    $data->t_name,
                    new Tournament\TournamentTemplate(
                        $data->tt_id,
                        $data->tt_name,
                        new Sport(
                            $data->sport_id,
                            $data->sport_name
                        ), $data->tt_gender
                    )
                )
            )
        );

        $standingBuilder = $this->queryBuilderStandingData($data->ts_id);
        foreach ($this->fetchObjects($standingBuilder) as $object) {
            $participant = new Standing\Participant(
                $object->p_id,
                $object->p_name,
                $object->p_type,
                $object->country_name,
                $this->createImage($object->i_contenttype, $object->i_value)
            );

            $standing->addStanding(
                new Standing\Stand(
                    $participant,
                    $this->queryBuilderStandingDataValues($object->standing_p_id, 'total')
                )
            );
            $standing->addHomeStanding(
                new Standing\Stand(
                    $participant,
                    $this->queryBuilderStandingDataValues($object->standing_p_id, 'home')
                )
            );
            $standing->addAwayStanding(
                new Standing\Stand(
                    $participant,
                    $this->queryBuilderStandingDataValues($object->standing_p_id, 'away')
                )
            );
        }

        return $standing;
    }

    protected function queryBuilder($stageId) : QueryBuilder
    {
        $qb = $this->getBuilder();

        $qb
            // Tournament stage
            ->select('ts.id as ts_id', 'ts.name as ts_name', 'ts.gender as ts_gender', 'ts.startdate as ts_startdate', 'ts.enddate as ts_enddate')
            ->from('tournament_stage', 'ts')
            ->andWhere($qb->expr()->eq('ts.id', ':stage'))
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

            // Standing
            ->innerJoin('ts', 'standing', 'standing', 'ts.id = standing.objectFK AND standing.object = "tournament_stage" AND standing.del = "no"')
            ->addSelect('standing.id as standing_id')
            ->andWhere($qb->expr()->eq('standing.name', '"Ligatable"'))
            // Standing Type
            ->innerJoin('standing', 'standing_type', 'standing_type', 'standing.standing_typeFK = standing_type.id')
            ->addSelect('standing_type.id as standing_type_id', 'standing_type.name as standingtype_name', 'standing_type.description as standingtype_description')
            ->andWhere($qb->expr()->eq('standing_type.name', '"Ligatable"'))
        ;

        $qb->setParameter(':stage', $stageId);

        return $qb;
    }

    private function queryBuilderStandingData($stageId): QueryBuilder
    {
        $qb = $this->getBuilder();

        $qb
            // Tournament stage
            ->from('tournament_stage', 'ts')
            ->andWhere($qb->expr()->eq('ts.id', ':stage'))
            // Standing
            ->innerJoin('ts', 'standing', 'standing', 'ts.id = standing.objectFK AND standing.object = "tournament_stage" AND standing.del = "no"')
            ->andWhere($qb->expr()->eq('standing.name', '"Ligatable"'))
            // Standing Participants
            ->innerJoin('standing', 'standing_participants', 'standing_p', 'standing.id = standing_p.standingFK')
            ->addSelect('standing_p.id as standing_p_id')
            // Participant
            ->innerJoin('standing_p', 'participant', 'p', 'standing_p.participantFK = p.id')
            ->addSelect('p.id as p_id', 'p.name as p_name', 'p.gender as p_gender', 'p.type as p_type')
            // Country
            ->innerJoin('p', 'country', 'c', 'p.countryFK = c.id')
            ->addSelect('c.name as country_name')
            // Logo
            ->leftJoin('p', 'image', 'i', 'p.id = i.objectFK AND i.object = "participant"')
            ->addSelect('i.value as i_value', 'i.contenttype as i_contenttype')
            ->andWhere($qb->expr()->eq('i.type', '"logo"'))
        ;

        $qb->setParameter(':stage', $stageId);

        return $qb;
    }

    private function queryBuilderStandingDataValues($id, $type): Standing\StandData
    {
        static $standingData = [];

        if (!isset($standingData[$id])) {
            $qb = $this->getBuilder();
            $qb
                ->from('standing_data', 'sd')
                ->addSelect('sd.standing_type_paramFK as sd_type', 'sd.value as sd_value', 'sd.code as sd_code')
                ->andWhere($qb->expr()->eq('sd.standing_participantsFK', $id));

            foreach ($this->fetchObjects($qb) as $data) {
                $standingData[$id][$data->sd_code] = $data->sd_value;
            }
        }

        $key = '';
        if ($type === 'home') {
            $key = 'home';
        }

        if ($type === 'away') {
            $key = 'away';
        }

        $points = 0;
        $pointsKey = $standingData[$id]['points' . $key] ?? null;
        if (null !== $pointsKey) {
            $points = $pointsKey;
        } else {
            $points += $standingData[$id]['wins' . $key] * 3;
            $points += $standingData[$id]['draws' . $key];
        }

        return new Standing\StandData(
            $standingData[$id]['rank'],
            $standingData[$id]['played' . $key],
            $standingData[$id]['wins' . $key],
            $standingData[$id]['draws' . $key],
            $standingData[$id]['defeits' . $key],
            $standingData[$id]['goalsfor' . $key],
            $standingData[$id]['goalsagainst' . $key],
            $points
        );
    }

}
