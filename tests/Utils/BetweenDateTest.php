<?php

namespace SDM\Enetpulse\Tests\Utils;

use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Utils\BetweenDate;

class BetweenDateTest extends TestCase
{
    public function testBetweenDate(): void
    {
        $between1 = new BetweenDate(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $between1->getFromDate());
        $this->assertInstanceOf(\DateTime::class, $between1->getToDate());

        $between2 = new BetweenDate(new \DateTime(), new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $between2->getFromDate());
        $this->assertInstanceOf(\DateTime::class, $between2->getToDate());
    }
}
