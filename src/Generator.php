<?php

namespace SDM\Enetpulse;

use SDM\Enetpulse\Provider\MatchProvider;
use SDM\Enetpulse\Provider\OddsProvider;
use SDM\Enetpulse\Provider\SportProvider;
use SDM\Enetpulse\Provider\TeamProvider;
use SDM\Enetpulse\Provider\TournamentProvider;

class Generator
{
    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function getMatchProvider(): MatchProvider
    {
        return new MatchProvider($this->authentication);
    }

    public function getOddsProvider(): OddsProvider
    {
        return new OddsProvider($this->authentication);
    }

    public function getTournamentProvider(): TournamentProvider
    {
        return new TournamentProvider($this->authentication);
    }

    public function getTeamProvider(): TeamProvider
    {
        return new TeamProvider($this->authentication);
    }

    public function getSportProvider(): SportProvider
    {
        return new SportProvider($this->authentication);
    }
}
