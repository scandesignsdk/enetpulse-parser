<?php

namespace SDM\Enetpulse\Model\Odds;

class AsianHandicapOdds
{
    /**
     * @var float
     */
    private $goals;

    /**
     * @var AsianHandicap[]
     */
    private $odds;

    /**
     * @param float $goals
     * @param AsianHandicap[] $odds
     */
    public function __construct(float $goals, array $odds = [])
    {
        $this->goals = $goals;
        $this->odds = $odds;
    }

    public function getGoals(): float
    {
        return $this->goals;
    }

    /**
     * @return AsianHandicap[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }

    public function addOdds(AsianHandicap $handicap): self
    {
        $this->odds[] = $handicap;
        return $this;
    }

    /**
     * Calculate the handicap score
     *
     * @return string
     */
    public function calculateHandicap(): string
    {
        $homeScore = 0;
        $awayScore = 0;

        if ($this->getGoals() > 0) {
            $homeScore = $this->getGoals();
        } elseif ($this->getGoals() < 0) {
            $awayScore = abs($this->getGoals());
        }

        return $homeScore . ' - ' . $awayScore;
    }
}
