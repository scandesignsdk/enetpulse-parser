<?php

namespace SDM\Enetpulse\Tests\Provider;

use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Provider\SportProvider;
use SDM\Enetpulse\Utils\Data;

class SportProviderTest extends AbstractProviderTest
{
    private function getProvider($return): SportProvider
    {
        return $this->createProvider(SportProvider::class, $return);
    }

    public function testGetSports(): void
    {
        $provider = $this->getProvider([
            new Data(['id' => 1, 'name' => 'test1']),
            new Data(['id' => 2, 'name' => 'test2']),
        ]);
        $sports = $provider->getSports();

        $this->assertCount(2, $sports);
        $this->assertInstanceOf(Sport::class, $sports[1]);
        $this->assertSame(2, $sports[1]->getId());
        $this->assertSame('test2', $sports[1]->getName());
    }

    public function testGetSportByName(): void
    {
        $provider = $this->getProvider(new Data(['id' => 1, 'name' => 'test1']));
        $sport = $provider->getSportByName('test1');
        $this->assertInstanceOf(Sport::class, $sport);
        $this->assertSame(1, $sport->getId());
        $this->assertSame('test1', $sport->getName());
    }

    /**
     * @group mysql
     * @requires extension pdo_mysql
     */
    public function testMysqlGetSports(): void
    {
        $sports = $this->getProvider(null)->getSports();
        $this->assertCount(2, $sports);
    }

    /**
     * @group mysql
     * @requires extension pdo_mysql
     */
    public function testMysqlGetSportByName(): void
    {
        $sport = $this->getProvider(null)->getSportByName('soccer');
        $this->assertInstanceOf(Sport::class, $sport);
    }
}
