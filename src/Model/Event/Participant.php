<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model\Event;

use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Model\Participant\Result;
use SDM\Enetpulse\Model\Standing\Participant as BaseParticipant;

class Participant extends BaseParticipant
{
    /**
     * @var Odds[]|null
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
     * @param string   $image
     * @param Odds[]|null   $odds
     * @param Result[] $results
     * @param Event    $event
     */
    public function __construct(int $id, string $name, string $type, string $country, string $image, array $odds = null, array $results, Event $event)
    {
        parent::__construct($id, $name, $type, $country, $image);
        $this->odds = $odds;
        $this->results = $results;
        $this->event = $event;
    }

    /**
     * @return Odds[]|null
     */
    public function getOdds(): ?array
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
