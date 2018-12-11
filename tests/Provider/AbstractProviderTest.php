<?php

namespace SDM\Enetpulse\Tests\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Configuration;

abstract class AbstractProviderTest extends TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Configuration
     */
    public $configuration;

    public function setUp()
    {
        $this->configuration = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        if (\in_array('mysql', $this->getGroups(), true)) {
            $this->setUpMysql();
        }
    }

    protected function setUpMysql(): void
    {
        try {
            $this->connection = DriverManager::getConnection(['url' => $_ENV['DATABASE_URL']]);
            $user = $this->connection->getUsername();
            $pass = $this->connection->getPassword();
            $db = $this->connection->getDatabase();
            if ($pass) {
                `MYSQL_PWD=$pass mysql -u $user -e "DROP DATABASE IF EXISTS $db"`;
                `MYSQL_PWD=$pass mysql -u $user -e "CREATE DATABASE $db"`;
            } else {
                `mysql -u $user -e "DROP DATABASE IF EXISTS $db"`;
                `mysql -u $user -e "CREATE DATABASE $db"`;
            }
            $this->configuration = new Configuration($_ENV['DATABASE_URL']);
            $this->configuration->getConnection()->exec(file_get_contents(__DIR__.'/../fixture.sql'));
            if (\in_array('event', $this->getGroups(), true)) {
                $this->configuration->getConnection()->exec(file_get_contents(__DIR__.'/../eventfixture.sql'));
            }
        } catch (DBALException $exception) {
            echo $exception->getMessage();
            exit(1);
        }
    }

    /**
     * @covers \SDM\Enetpulse\Provider\AbstractProvider::fetchObjects
     * @covers \SDM\Enetpulse\Provider\AbstractProvider::fetchSingle
     *
     * @param string             $className
     * @param mixed              $return
     * @param Configuration|null $configuration
     *
     * @return mixed
     */
    protected function createProvider(string $className, $return, Configuration $configuration = null)
    {
        if (!\in_array('mysql', $this->getGroups(), true)) {
            $mock = $this->getMockBuilder($className)
                ->setMethods(['getBuilder', 'fetchObjects', 'fetchSingle'])
                ->setConstructorArgs([$configuration ?: $this->configuration])
                ->getMock()
            ;
            $mock
                ->method('getBuilder')
                ->willReturn($this->getQueryBuilder())
            ;
            $mock
                ->method('fetchObjects')
                ->willReturn($return)
            ;
            $mock
                ->method('fetchSingle')
                ->willReturn($return)
            ;

            return $mock;
        }

        return new $className($this->configuration);
    }

    private function getQueryBuilder(): QueryBuilder
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $expr = $this->getMockBuilder(ExpressionBuilder::class)->disableOriginalConstructor()->getMock();
        $qb = $this->getMockBuilder(QueryBuilder::class)->setConstructorArgs([$connection])->getMock();
        $qb
            ->method('expr')
            ->willReturn($expr)
        ;
        $qb
            ->method('select')
            ->willReturn($qb)
        ;
        $qb
            ->method('addSelect')
            ->willReturn($qb)
        ;
        $qb
            ->method('from')
            ->willReturn($qb)
        ;
        $qb
            ->method('where')
            ->willReturn($qb)
        ;
        $qb
            ->method('setParameter')
            ->willReturn($qb)
        ;
        $qb
            ->method('innerJoin')
            ->willReturn($qb)
        ;
        $qb
            ->method('addOrderBy')
            ->willReturn($qb)
        ;
        $qb
            ->method('andWhere')
            ->willReturn($qb)
        ;

        /* @var QueryBuilder $qb */
        return $qb;
    }
}
