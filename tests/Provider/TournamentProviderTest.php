<?php

namespace SDM\Enetpulse\Tests\Provider;

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Model\Sport;
use SDM\Enetpulse\Model\Tournament;
use SDM\Enetpulse\Provider\TournamentProvider;
use SDM\Enetpulse\Utils\Data;

class TournamentProviderTest extends AbstractProviderTest
{
    private function getProvider($return, Configuration $configuration = null): TournamentProvider
    {
        return $this->createProvider(TournamentProvider::class, $return, $configuration);
    }

    public function dataProvider(): array
    {
        return [
            [
                'Data set 1',
                [
                    new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 10436, 't_name' => '2016/2017', 'ts_id' => 844755, 'ts_name' => 'Champions League Qualification', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
                    new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 10436, 't_name' => '2016/2017', 'ts_id' => 846089, 'ts_name' => 'Champions Legaue Grp. A', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
                    new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 11542, 't_name' => '2017/2018', 'ts_id' => 851349, 'ts_name' => 'Champions League Grp. H', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
                    new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 11542, 't_name' => '2017/2018', 'ts_id' => 851350, 'ts_name' => 'Champions League Grp. E', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
                    new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 11542, 't_name' => '2017/2018', 'ts_id' => 852199, 'ts_name' => 'Champions League Final Stage', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $setName
     * @param array  $data
     */
    public function testGetTournaments(string $setName, array $data): void
    {
        $provider = $this->getProvider($data);
        $results = $provider->getTournaments(new Sport(1, 'test1'));
        $this->assertCount(2, $results, $setName);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $setName
     * @param array  $data
     */
    public function testGetTournamentsByTemplate(string $setName, array $data): void
    {
        $provider = $this->getProvider($data);
        $results = $provider->getTournamentsByTemplate(1);
        $this->assertCount(2, $results, $setName);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $setName
     * @param array  $data
     */
    public function testGetTournamentsByCountry(string $setName, array $data): void
    {
        $provider = $this->getProvider($data);
        $results = $provider->getTournamentsByCountry('DK');
        $this->assertCount(2, $results, $setName);
        // Tournament
        $tournament = $results[0];
        $this->assertInstanceOf(Tournament::class, $tournament);
        $this->assertSame(10436, $tournament->getId());
        $this->assertSame('2016/2017', $tournament->getName());
        // TournamentTemplate
        $this->assertSame(42, $tournament->getTemplate()->getId());
        $this->assertSame('Champions League', $tournament->getTemplate()->getName());
        $this->assertSame('Soccer', $tournament->getTemplate()->getSport()->getName());
        $this->assertSame('male', $tournament->getTemplate()->getGender());

        // Stage
        $this->assertCount(2, $tournament->getStages());
        $stage = $tournament->getStages()[1];
        $this->assertInstanceOf(Tournament\TournamentStage::class, $stage);
        $this->assertSame(846089, $stage->getId());
        $this->assertSame('Champions Legaue Grp. A', $stage->getName());
        $this->assertSame('International', $stage->getCountry());
        $this->assertSame('28-06-2016', $stage->getStartDate()->format('d-m-Y'));
        $this->assertSame('24-08-2016', $stage->getEndDate()->format('d-m-Y'));
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $setName
     * @param array  $data
     */
    public function testGetTournamentsByCountryBySport(string $setName, array $data): void
    {
        $provider = $this->getProvider($data);
        $results = $provider->getTournamentsByCountry('DK', new Sport(1, 'test1'));
        $this->assertCount(2, $results, $setName);
    }

    public function testGetTournamentByTournamentId(): void
    {
        $provider = $this->getProvider([
            new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 10436, 't_name' => '2016/2017', 'ts_id' => 844755, 'ts_name' => 'Champions League Qualification', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
            new Data(['tt_id' => 42, 'tt_name' => 'Champions League', 'tt_gender' => 'male', 't_id' => 10436, 't_name' => '2016/2017', 'ts_id' => 846089, 'ts_name' => 'Champions Legaue Grp. A', 'ts_gender' => 'male', 'ts_startdate' => '2016-06-28 00:00:00', 'ts_enddate' => '2016-08-24', 'country_id' => 11, 'country_name' => 'International', 'sport_id' => 1, 'sport_name' => 'Soccer']),
        ]);
        $result = $provider->getTournamentByTournamentId(1);
        $this->assertInstanceOf(Tournament::class, $result);
    }

    public function testEmptyTournament(): void
    {
        $provider = $this->getProvider([]);
        $this->assertNull($provider->getTournamentByTournamentId(1));
    }

    public function testSetStages(): void
    {
        /** @var Tournament $mock */
        $tournament = new Tournament(
            1,
            'test',
            new Tournament\TournamentTemplate(
                1,
                'test1',
                new Sport(
                    1,
                    'test1'
                ),
                'male'
            )
        );
        $tournament->setStages([
            new Tournament\TournamentStage(1, '1', 'DK', new \DateTime(), new \DateTime(), $tournament),
            new Tournament\TournamentStage(1, '1', 'DK', new \DateTime(), new \DateTime(), $tournament),
            new Tournament\TournamentStage(1, '1', 'DK', new \DateTime(), new \DateTime(), $tournament),
        ]);
        $this->assertCount(3, $tournament->getStages());
    }

    public function mysqlDataprovider(): array
    {
        return [
            [null, 4],
            [new Sport(1, 'Soccer'), 4],
            [new Sport(2, 'test'), 0],
        ];
    }

    /**
     * @dataProvider mysqlDataprovider
     * @group mysql
     * @requires extension pdo_mysql
     *
     * @param null|Sport $sport
     * @param $expected
     */
    public function testMysqlGetTournaments(?Sport $sport, $expected): void
    {
        $tournaments = $this->getProvider(null)->getTournaments($sport, false);
        $this->assertCount($expected, $tournaments);
    }

    /**
     * @group mysql
     * @requires extension pdo_mysql
     */
    public function testMysqlGetActiveTournaments(): void
    {
        $tournaments = $this->getProvider(null)->getTournaments(null, true);
        $this->assertCount(2, $tournaments);
    }

    /**
     * @group mysql
     * @requires extension pdo_mysql
     */
    public function testMysqlTournament(): void
    {
        $tournament = $this->getProvider(null)->getTournamentByStageId(850150);
        $this->assertInstanceOf(Tournament::class, $tournament);

        $this->assertCount(1, $tournament->getStages());
        $this->assertSame('2017/2018', $tournament->getName());
        $this->assertSame('Champions League Qualification', $tournament->getStages()[0]->getName());
        $this->assertInstanceOf(Tournament::class, $tournament->getStages()[0]->getTournament());
    }

    /**
     * @group mysql
     * @requires extension pdo_mysql
     */
    public function testMysqlTournamentConfiguration(): void
    {
        $configuration = $this->configuration;
        $configuration->setTournamentTemplates([42]);

        $tournaments = $this->getProvider(null, $configuration)->getTournaments(null, false);
        $this->assertCount(2, $tournaments);
    }
}
