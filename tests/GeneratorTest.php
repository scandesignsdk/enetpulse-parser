<?php

namespace SDM\Enetpulse\Tests;

use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;
use SDM\Enetpulse\Provider\EventProvider;
use SDM\Enetpulse\Provider\OddsProvider;
use SDM\Enetpulse\Provider\SportProvider;
use SDM\Enetpulse\Provider\TournamentProvider;

class GeneratorTest extends TestCase
{
    public function testGenerator(): void
    {
        $generator = new Generator(new Configuration('dsn'));
        $this->assertInstanceOf(EventProvider::class, $generator->getEventProvider());
        $this->assertInstanceOf(OddsProvider::class, $generator->getOddsProvider());
        $this->assertInstanceOf(TournamentProvider::class, $generator->getTournamentProvider());
        $this->assertInstanceOf(SportProvider::class, $generator->getSportProvider());
    }
}
