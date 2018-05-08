<?php

namespace SDM\Enetpulse\Model\Standing;

class StandData
{

    /**
     * @var int
     */
    private $rank;

    /**
     * @var int
     */
    private $played;

    /**
     * @var int
     */
    private $wins;

    /**
     * @var int
     */
    private $draws;

    /**
     * @var int
     */
    private $defeits;

    /**
     * @var int
     */
    private $goalsFor;

    /**
     * @var int
     */
    private $goalsAgainst;

    /**
     * @var int
     */
    private $points;

    public function __construct(int $rank, int $played, int $wins, int $draws, int $defeits, int $goalsFor, int $goalsAgainst, int $points)
    {
        $this->rank = $rank;
        $this->played = $played;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->defeits = $defeits;
        $this->goalsFor = $goalsFor;
        $this->goalsAgainst = $goalsAgainst;
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->played;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @return int
     */
    public function getDraws(): int
    {
        return $this->draws;
    }

    /**
     * @return int
     */
    public function getDefeits(): int
    {
        return $this->defeits;
    }

    /**
     * @return int
     */
    public function getGoalsFor(): int
    {
        return $this->goalsFor;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst(): int
    {
        return $this->goalsAgainst;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

}
