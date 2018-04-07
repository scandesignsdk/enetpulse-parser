<?php

namespace SDM\Enetpulse\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Authentication;

class AuthenticationTest extends TestCase
{

    public function testGetConnection(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $mock = $this->getMockBuilder(Authentication::class)->setConstructorArgs(['dsn'])->getMock();
        $mock->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection)
        ;

        /** @var Authentication $mock */
        $this->assertInstanceOf(Authentication::class, $mock);
        $this->assertInstanceOf(Connection::class, $mock->getConnection());
    }

    public function testFailConnection(): void
    {
        $this->expectException(DBALException::class);
        $auth = new Authentication('bad_dsn');
        $auth->getConnection();
    }

    /**
     * @requires extension pdo_sqlite
     */
    public function testConnection(): void
    {
        $auth = new Authentication('sqlite://memory');
        $this->assertInstanceOf(Connection::class, $auth->getConnection());
        $this->assertInstanceOf(Connection::class, $auth->getConnection());
    }
}
