<?php

namespace SDM\Enetpulse\Model\Event;

use SDM\Enetpulse\Model\Odds;

class Participant
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
    private $type;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string|null
     */
    private $score;

    /**
     * @var string|null
     */
    private $scoreType;

    /**
     * @var Odds[]
     */
    private $odds;

    /**
     * @param int         $id
     * @param string      $name
     * @param string      $type
     * @param string      $country
     * @param string|null $score
     * @param string|null $scoreType
     * @param Odds[]      $odds
     */
    public function __construct(int $id, string $name, string $type, string $country, ?string $score, ?string $scoreType, array $odds = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->country = $country;
        $this->score = $score;
        $this->scoreType = $scoreType;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function getScoreType(): ?string
    {
        return $this->scoreType;
    }

    /**
     * @return Odds[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }
}
