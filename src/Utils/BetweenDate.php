<?php

namespace SDM\Enetpulse\Utils;

class BetweenDate
{
    /**
     * @var \DateTime
     */
    private $fromDate;

    /**
     * @var \DateTime
     */
    private $toDate;

    public function __construct(\DateTime $fromDate, \DateTime $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate ?: new \DateTime('now');
    }

    public function getFromDate(): \DateTime
    {
        return $this->fromDate;
    }

    public function getToDate(): \DateTime
    {
        return $this->toDate;
    }
}
