<?php

namespace SDM\Enetpulse\Provider;

use PHPUnit\Framework\MockObject\Builder\Match;
use SDM\Enetpulse\Model\Tournament;
use SDM\Enetpulse\Utils\BetweenDate;

class MatchProvider extends AbstractProvider
{
    /**
     * @param int              $howMany
     * @param Tournament[]     $tournaments
     * @param BetweenDate|null $betweenDate
     *
     * @return Match[]
     */
    public function getUpcomingMatches(int $howMany = 30, array $tournaments = [], BetweenDate $betweenDate = null): array
    {
    }

    /**
     * @param int              $howMany
     * @param Tournament[]     $tournaments
     * @param BetweenDate|null $betweenDate
     *
     * @return Match[]
     */
    public function getResults(int $howMany = 30, array $tournaments = [], BetweenDate $betweenDate = null): array
    {
    }
}
