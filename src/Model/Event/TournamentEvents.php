<?php

namespace SDM\Enetpulse\Model\Event;

use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Tournament\TournamentStage;

class TournamentEvents
{
    /**
     * @var TournamentStage
     */
    private $tournamentStage;

    /**
     * @var Event[]
     */
    private $events;

    /**
     * @param TournamentStage $tournamentStage
     * @param Event[] $events
     */
    public function __construct(TournamentStage $tournamentStage, array $events = [])
    {
        $this->tournamentStage = $tournamentStage;
        $this->events = $events;
    }

    public function getTournamentStage(): TournamentStage
    {
        return $this->tournamentStage;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        $this->events[] = $event;

        return $this;
    }
}
