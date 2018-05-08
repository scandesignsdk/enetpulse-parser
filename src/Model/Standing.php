<?php

namespace SDM\Enetpulse\Model;

use SDM\Enetpulse\Model\Standing\Stand;
use SDM\Enetpulse\Model\Tournament\TournamentStage;

class Standing
{
    /**
     * @var Stand[]|null
     */
    private $standings;

    /**
     * @var Stand[]|null
     */
    private $homeStandings;

    /**
     * @var Stand[]|null
     */
    private $awayStandings;

    /**
     * @var TournamentStage
     */
    private $stage;

    /**
     * @param TournamentStage $stage
     * @param Stand[]|null $standings
     * @param Stand[]|null $homeStandings
     * @param Stand[]|null $awayStandings
     */
    public function __construct(TournamentStage $stage, array $standings = null, array $homeStandings = null, array $awayStandings = null)
    {
        $this->stage = $stage;
        $this->standings = $standings;
        $this->homeStandings = $homeStandings;
        $this->awayStandings = $awayStandings;
    }

    public function addStanding(Stand $stand): void
    {
        $this->standings[] = $stand;
    }

    public function addHomeStanding(Stand $stand): void
    {
        $this->homeStandings[] = $stand;
    }

    public function addAwayStanding(Stand $stand): void
    {
        $this->awayStandings[] = $stand;
    }

    /**
     * @return Stand[]
     */
    public function getTotalStandings(): array
    {
        $standings = $this->standings;
        usort($standings, function (Stand $a, Stand $b) {
            return $a->getData()->getRank() <=> $b->getData()->getRank();
        });
        return $standings;
    }

    /**
     * @return Stand[]
     */
    public function getHomeStandings(): array
    {
        $standings = $this->homeStandings;
        usort($standings, function (Stand $a, Stand $b) {
            return $a->getData()->getRank() <=> $b->getData()->getRank();
        });
        return $standings;
    }

    /**
     * @return Stand[]
     */
    public function getAwayStandings(): array
    {
        $standings = $this->awayStandings;
        usort($standings, function (Stand $a, Stand $b) {
            return $a->getData()->getRank() <=> $b->getData()->getRank();
        });
        return $standings;
    }

    /**
     * @return TournamentStage
     */
    public function getStage(): TournamentStage
    {
        return $this->stage;
    }
}
