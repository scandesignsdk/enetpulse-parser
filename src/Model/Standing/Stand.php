<?php

namespace SDM\Enetpulse\Model\Standing;

class Stand
{
    /**
     * @var Participant
     */
    private $participant;

    /**
     * @var StandData
     */
    private $data;

    public function __construct(Participant $participant, StandData $data)
    {
        $this->participant = $participant;
        $this->data = $data;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function getData(): StandData
    {
        return $this->data;
    }
}
