<?php

namespace SDM\Enetpulse\Model\Event;

use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Model\Participant\Result;

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
     * @var Odds[]
     */
    private $odds;

    /**
     * @var Result[]
     */
    private $results;

    /**
     * @var Event
     */
    private $event;

    /**
     * @param int      $id
     * @param string   $name
     * @param string   $type
     * @param string   $country
     * @param Odds[]   $odds
     * @param Result[] $results
     * @param Event    $event
     */
    public function __construct(int $id, string $name, string $type, string $country, array $odds, array $results, Event $event)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->country = $country;
        $this->odds = $odds;
        $this->results = $results;
        $this->event = $event;
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

    /**
     * @return Odds[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }

    /**
     * @return Result[]
     */
    public function getResults(): array
    {
        $results = $this->results;
        usort($results, function (Result $a, Result $b) {
            return $b->getUpdated() <=> $a->getUpdated();
        });

        return $results;
    }

    public function getFirstResult(): ?Result
    {
        if ('notstarted' === $this->event->getStatus()) {
            return null;
        }

        return $this->getResults()[0];
    }

    public function addResult(Result $result): self
    {
        $this->results[] = $result;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
