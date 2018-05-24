<?php

namespace SDM\Enetpulse\Model\Odds;

class OverUnderGoalsOdds
{
    /**
     * @var float
     */
    private $goals;

    /**
     * @var OverUnderOdds[]
     */
    private $odds;

    /**
     * @param float $goals
     * @param OverUnderOdds[] $odds
     */
    public function __construct(float $goals, array $odds)
    {
        $this->goals = $goals;
        $this->odds = $odds;
    }

    /**
     * @return float
     */
    public function getGoals(): float
    {
        return $this->goals;
    }

    /**
     * @return OverUnderOdds[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }
}
