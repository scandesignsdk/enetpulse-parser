<?php

namespace SDM\Enetpulse\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Authentication;
use SDM\Enetpulse\Generator;
use SDM\Enetpulse\Provider\MatchProvider;
use SDM\Enetpulse\Provider\OddsProvider;
use SDM\Enetpulse\Provider\SportProvider;
use SDM\Enetpulse\Provider\TeamProvider;
use SDM\Enetpulse\Provider\TournamentProvider;

class GeneratorTest extends TestCase
{

    public function testGenerator(): void
    {
        $generator = new Generator(new Authentication('dsn'));
        $this->assertInstanceOf(MatchProvider::class, $generator->getMatchProvider());
        $this->assertInstanceOf(OddsProvider::class, $generator->getOddsProvider());
        $this->assertInstanceOf(TournamentProvider::class, $generator->getTournamentProvider());
        $this->assertInstanceOf(TeamProvider::class, $generator->getTeamProvider());
        $this->assertInstanceOf(SportProvider::class, $generator->getSportProvider());
    }

}
