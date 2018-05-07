<?php

namespace SDM\Enetpulse\Model\Odds;

use SDM\Enetpulse\Model\Event;

class Handicap
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var Event\Participant
     */
    private $participant;

    /**
     * @var float|int
     */
    private $handicap;

    /**
     * @var Offer[]
     */
    private $offers;

    /**
     * @param Event $event
     * @param Event\Participant $participant
     * @param float|int $handicap
     * @param Offer[] $offers
     */
    public function __construct(Event $event, Event\Participant $participant, $handicap, array $offers)
    {
        $this->event = $event;
        $this->participant = $participant;
        $this->handicap = $handicap;
        $this->offers = $offers;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getParticipant(): Event\Participant
    {
        return $this->participant;
    }

    /**
     * @return float|int
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }
}
