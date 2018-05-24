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

}
