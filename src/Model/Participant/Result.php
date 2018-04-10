<?php

namespace SDM\Enetpulse\Model\Participant;

class Result
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @var string
     */
    private $resultCode;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @param mixed     $result
     * @param string    $resultCode
     * @param \DateTime $updated
     */
    public function __construct($result, string $resultCode, \DateTime $updated)
    {
        $this->result = $result;
        $this->resultCode = $resultCode;
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    public function getResultCode(): string
    {
        return $this->resultCode;
    }

    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }
}
