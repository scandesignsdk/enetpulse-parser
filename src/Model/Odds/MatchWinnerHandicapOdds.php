<?php

namespace SDM\Enetpulse\Model\Odds;

class MatchWinnerHandicapOdds
{
    /**
     * @var Handicap
     */
    private $handicap;

    /**
     * @var MatchWinnerOdds[]
     */
    private $winnerOdds;

    /**
     * @param Handicap $handicap
     * @param MatchWinnerOdds[] $winnerOdds
     */
    public function __construct(Handicap $handicap, array $winnerOdds)
    {
        $this->handicap = $handicap;
        $this->winnerOdds = $winnerOdds;
    }

    public function getHandicap(): Handicap
    {
        return $this->handicap;
    }

    /**
     * @return MatchWinnerOdds[]
     */
    public function getWinnerOdds(): array
    {
        return $this->winnerOdds;
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

        if ($this->getHandicap()->getHandicap() > 0) {
            $homeScore = $this->getHandicap()->getHandicap();
        } elseif ($this->getHandicap()->getHandicap() < 0) {
            $awayScore = abs($this->getHandicap()->getHandicap());
        }

        return $homeScore . ' - ' . $awayScore;
    }
}
