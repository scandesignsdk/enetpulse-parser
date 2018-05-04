<?php

declare(strict_types=1);

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
     * @var array|Odds[]
     */
    private $odds;

    /**
     * @param int $id
     * @param string $name
     * @param TournamentStage $stage
     * @param \DateTime $startDate
     * @param string $status
     * @param Participant[] $participants
     * @param array $odds
     */
    public function __construct(int $id, string $name, TournamentStage $stage, \DateTime $startDate, string $status, array $participants = [], array $odds = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->stage = $stage;
        $this->startDate = $startDate;
        $this->status = $status;
        $this->participants = $participants;
        $this->odds = $odds;
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

    /**
     * @return array|Odds[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }

    /**
     * @param array|Odds[] $odds
     *
     * @return self
     */
    public function setOdds(array $odds): self
    {
        $this->odds = $odds;

        return $this;
    }

    /**
     * @return Odds[]
     */
    public function getOrd1x2HomeTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[0]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
            ;
        });
        return array_values($filtered);
    }

    /**
     * @return Odds[]
     */
    public function getOrd1x2DrawOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'draw'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
            ;
        });
        return array_values($filtered);
    }

    /**
     * @return Odds[]
     */
    public function getOrd1x2AwayTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[1]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
            ;
        });
        return array_values($filtered);
    }
}
