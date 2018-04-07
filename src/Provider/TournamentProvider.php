<?php

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Model\Tournament;

class TournamentProvider extends AbstractProvider
{
    /**
     * @param Sport $sport
     *
     * @return Tournament[]
     */
    public function getTournaments(Sport $sport): array
    {
    }

    public function getTournamentByName(string $name): ?Tournament
    {
    }

    public function getTournamentById(int $id): ?Tournament
    {
    }
}
