<?php

namespace SDM\Enetpulse\Model\Odds;

use SDM\Enetpulse\Model\Event\Participant;

class HalftimeFullTime
{
    /**
     * @var null|Participant
     */
    private $halftimeParticipant;

    /**
     * @var null|Participant
     */
    private $fullTimeParticipant;

    /**
     * @var Offer[]
     */
    private $offers;

    /**
     * @param null|Participant $halftimeParticipant
     * @param null|Participant $fullTimeParticipant
     * @param Offer[] $offers
     */
    public function __construct(?Participant $halftimeParticipant, ?Participant $fullTimeParticipant, array $offers)
    {
        $this->halftimeParticipant = $halftimeParticipant;
        $this->fullTimeParticipant = $fullTimeParticipant;
        $this->offers = $offers;
    }

    public function getHalftimeParticipant(): ?Participant
    {
        return $this->halftimeParticipant;
    }

    public function getFullTimeParticipant(): ?Participant
    {
        return $this->fullTimeParticipant;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }
}
