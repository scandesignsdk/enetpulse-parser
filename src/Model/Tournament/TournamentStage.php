<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model\Tournament;

use SDM\Enetpulse\Model\Tournament;

class TournamentStage
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
     * @var string
     */
    private $country;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var Tournament
     */
    private $tournament;

    public function __construct(int $id, string $name, string $country, \DateTime $startDate, \DateTime $endDate, Tournament $tournament)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->tournament = $tournament;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }
}
