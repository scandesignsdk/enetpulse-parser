<?php

namespace SDM\Enetpulse\Model;

use SDM\Enetpulse\Model\Event\Participant;
use SDM\Enetpulse\Model\Tournament\TournamentStage;

class Event
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TournamentStage
     */
    private $stage;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var array|Participant[]
     */
    private $participants;

    /**
     * @param int             $id
     * @param string          $name
     * @param TournamentStage $stage
     * @param \DateTime       $startDate
     * @param string          $status
     * @param Participant[]   $participants
     */
    public function __construct(int $id, string $name, TournamentStage $stage, \DateTime $startDate, string $status, array $participants = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->stage = $stage;
        $this->startDate = $startDate;
        $this->status = $status;
        $this->participants = $participants;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStage(): TournamentStage
    {
        return $this->stage;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array|Participant[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param Participant[] $participants
     *
     * @return Event
     */
    public function setParticipants(array $participants): self
    {
        $this->participants = $participants;

        return $this;
    }
}
