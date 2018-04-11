<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model\Tournament;

use SDM\Enetpulse\Model\Sport;

class TournamentTemplate
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
     * @var Sport
     */
    private $sport;

    /**
     * @var string
     */
    private $gender;

    public function __construct(int $id, string $name, Sport $sport, string $gender)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sport = $sport;
        $this->gender = $gender;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSport(): Sport
    {
        return $this->sport;
    }

    public function getGender(): string
    {
        return $this->gender;
    }
}
