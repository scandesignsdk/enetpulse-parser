<?php

namespace SDM\Enetpulse\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Configuration;

class ConfigurationTest extends TestCase
{
    public function testGetConnection(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $mock = $this->getMockBuilder(Configuration::class)->setConstructorArgs(['dsn'])->getMock();
        $mock->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection)
        ;

        /* @var Configuration $mock */
        $this->assertInstanceOf(Configuration::class, $mock);
        $this->assertInstanceOf(Connection::class, $mock->getConnection());
    }

    public function testFailConnection(): void
    {
        $this->expectException(DBALException::class);
        $auth = new Configuration('bad_dsn');
        $auth->getConnection();
    }

    /**
     * @requires extension pdo_sqlite
     */
    public function testConnection(): void
    {
        $auth = new Configuration('sqlite://memory');
        $this->assertInstanceOf(Connection::class, $auth->getConnection());
        $this->assertInstanceOf(Connection::class, $auth->getConnection());
    }

    public function testConfiguration()
    {
        $config = new Configuration('');
        $config->setOddsProviders([1, 2, 3, 4, 5]);
        $this->assertCount(5, $config->getOddsProviders());

        $config->setTournamentTemplates([3, 4, 5]);
        $this->assertCount(3, $config->getTournamentTemplates());
    }
}
