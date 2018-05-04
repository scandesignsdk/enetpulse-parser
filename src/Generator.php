<?php

declare(strict_types=1);

namespace SDM\Enetpulse;

use SDM\Enetpulse\Provider\EventProvider;
use SDM\Enetpulse\Provider\ParticipantOddsProvider;
use SDM\Enetpulse\Provider\ParticipantProvider;
use SDM\Enetpulse\Provider\SportProvider;
use SDM\Enetpulse\Provider\TournamentProvider;

class Generator
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getEventProvider(): EventProvider
    {
        return new EventProvider($this->configuration);
    }

    public function getParticipantProvider(): ParticipantProvider
    {
        return new ParticipantProvider($this->configuration);
    }

    public function getOddsProvider(): ParticipantOddsProvider
    {
        return new ParticipantOddsProvider($this->configuration);
    }

    public function getTournamentProvider(): TournamentProvider
    {
        return new TournamentProvider($this->configuration);
    }

    public function getSportProvider(): SportProvider
    {
        return new SportProvider($this->configuration);
    }
}
