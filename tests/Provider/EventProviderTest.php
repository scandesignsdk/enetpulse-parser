<?php

namespace SDM\Enetpulse\Tests\Provider;

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Model\Participant\Result;
use SDM\Enetpulse\Model\Tournament;
use SDM\Enetpulse\Model\Tournament\TournamentStage;
use SDM\Enetpulse\Provider\EventProvider;
use SDM\Enetpulse\Utils\BetweenDate;

class EventProviderTest extends AbstractProviderTest
{
    private function getProvider($return, Configuration $configuration = null): EventProvider
    {
        return $this->createProvider(EventProvider::class, $return, $configuration);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetUpcoming(): void
    {
        $upcoming = $this->getProvider(null)->getUpcomingEvents();
        $this->assertCount(8, $upcoming);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetFinishedResults(): void
    {
        $results = $this->getProvider(null)->getFinishedEvents();
        $this->assertCount(15, $results);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetLive(): void
    {
        $results = $this->getProvider(null)->getLiveEvents();
        $this->assertCount(1, $results);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEvent(): void
    {
        $event = $this->getProvider(null)->getEvent(2680944);
        $this->assertInstanceOf(Event::class, $event);
        $this->assertSame(2680944, $event->getId());
        $this->assertSame('Sevilla-Manchester United', $event->getName());
        $this->assertInstanceOf(TournamentStage::class, $event->getStage());
        $this->assertSame('Champions League Final Stage', $event->getStage()->getName());
        $this->assertSame('21-02-2018', $event->getStartDate()->format('d-m-Y'));
        $this->assertSame('finished', $event->getStatus());

        // Participants
        $this->assertNotSame($event->getParticipants()[0]->getImage(), $event->getParticipants()[1]->getImage());
        $this->assertCount(2, $event->getParticipants(), 'participiants');
        $p = $event->getParticipants()[0];
        $this->assertSame(8302, $p->getId());
        $this->assertSame('Sevilla', $p->getName());
        $this->assertSame('Spain', $p->getCountry());
        $this->assertSame('team', $p->getType());
        $this->assertStringStartsWith('data:image/png', $p->getImage());
        $this->assertContains('saxORoJKrZz4GlVQWRWLcIdnoaChBsHEH2ilJ7LelflH1tRd8SJC7ezIwQ9rtsHPjS', $p->getImage());
        $this->assertInstanceOf(Result::class, $p->getFirstResult());
        $this->assertSame('0', $p->getFirstResult()->getResult());
        $this->assertSame('finalresult', $p->getFirstResult()->getResultCode());
        $this->assertSame('2018-02-22 07:35:14', $p->getFirstResult()->getUpdated()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(Event::class, $p->getEvent());
        // Results
        $this->assertCount(4, $p->getResults(), 'results');
        $r = $p->getResults()[1];
        $this->assertInstanceOf(Result::class, $r);
        $this->assertSame('0', $r->getResult());
        $this->assertSame('halftime', $r->getResultCode());
        $this->assertSame('2018-02-22 06:31:50', $r->getUpdated()->format('Y-m-d H:i:s'));

//        // Odds
//        $this->assertCount(2, $p->getOdds(), 'odds');
//        $o = $p->getOdds()[0];
//        $this->assertInstanceOf(Odds::class, $o);
//        $this->assertSame(394099719, $o->getId());
//        $this->assertSame('ord', $o->getScope());
//        $this->assertSame('win', $o->getSubtype());
//        // Offers
//        $this->assertCount(89, $o->getOffers(), 'offers');
//        $offer = $o->getOffers()[0];
//        $this->assertInstanceOf(Odds\Offer::class, $offer);
//        $this->assertSame(3141026432, $offer->getId());
//        $this->assertSame(2.71, $offer->getOdds());
//        $this->assertSame(2.77, $offer->getOldOdds());
//        $this->assertSame('2196&periodnumber=0', $offer->getCouponkey());
//        $this->assertNull($offer->getCurrency());
//        $this->assertSame(0, $offer->getVolume());
//        // Odds provider
//        $this->assertInstanceOf(Odds\Provider::class, $offer->getProvider());
//        $this->assertSame(7, $offer->getProvider()->getId());
//        $this->assertSame('Pinnacle Sports', $offer->getProvider()->getName());
//        $this->assertSame('Unknown', $offer->getProvider()->getCountry());
//        $this->assertSame('http://www.pinnaclesports.com/', $offer->getProvider()->getUrl());
//        $this->assertTrue($offer->getProvider()->isBookmaker());
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testEventOdds(): void
    {
        /** @var Event $event */
        $event = $this->getProvider(null)->getEvent(2680951);
        $this->assertCount(2, $event->getOdds());

        $home = $event->getOrd1x2HomeTeamOdds();
        $draw = $event->getOrd1x2DrawOdds();
        $away = $event->getOrd1x2AwayTeamOdds();

        $this->assertCount(1, $home);
        $this->assertCount(1, $draw);
        $this->assertCount(0, $away);

        // Odds
        $this->assertCount(1, $draw, 'odds');
        $o = $draw[0];
        $this->assertInstanceOf(Odds::class, $o);
        $this->assertSame(399663977, $o->getId());
        $this->assertSame('ord', $o->getScope());
        $this->assertSame('draw', $o->getSubtype());
        $this->assertSame(9773, $o->getIparam1());
        $this->assertSame(0, $o->getIparam2());
        $this->assertSame(0.0, $o->getDparam1());
        $this->assertSame(0.0, $o->getDparam2());
        $this->assertSame('', $o->getSparam());
        // Offers
        $this->assertCount(6, $o->getOffers(), 'offers');
        $offer = $o->getOffers()[0];
        $this->assertInstanceOf(Odds\Offer::class, $offer);
        $this->assertSame(3140726393, $offer->getId());
        $this->assertSame(7.6, $offer->getOdds());
        $this->assertSame(7.0, $offer->getOldOdds());
        $this->assertSame('football/market/1.137918059', $offer->getCouponkey());
        $this->assertSame('GBP', $offer->getCurrency());
        $this->assertSame(10, $offer->getVolume());
        // Odds provider
        $this->assertInstanceOf(Odds\Provider::class, $offer->getProvider());
        $this->assertSame(22, $offer->getProvider()->getId());
        $this->assertSame('Betfair Exchange', $offer->getProvider()->getName());
        $this->assertSame('Great Britain', $offer->getProvider()->getCountry());
        $this->assertSame('https://www.betfair.com/exchange/', $offer->getProvider()->getUrl());
        $this->assertFalse($offer->getProvider()->isBookmaker());
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testResult(): void
    {
        $event = $this->getProvider(null)->getEvent(2497097);
        $this->assertSame('3', $event->getParticipants()[0]->getFirstResult()->getResult());
        $this->assertSame('1', $event->getParticipants()[1]->getFirstResult()->getResult());
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetNoResult(): void
    {
        $event = $this->getProvider(null)->getEvent(2680956);
        $p = $event->getParticipants()[0];
        $this->assertNull($p->getFirstResult());
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetNullEvent(): void
    {
        $event = $this->getProvider(null)->getEvent(10);
        $this->assertNull($event);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventLimit5(): void
    {
        $events = $this->getProvider(null)->getUpcomingEvents(5);
        $this->assertCount(5, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventSort(): void
    {
        $events = $this->getProvider(null)->getUpcomingEvents(1);
        $this->assertSame('Paris Saint Germain-Real Madrid', $events[0]->getName());

        $events = $this->getProvider(null)->getLiveEvents(1);
        $this->assertSame('Bayern Munich-Besiktas', $events[0]->getName());

        $events = $this->getProvider(null)->getFinishedEvents(1);
        $this->assertSame('Shakhtar Donetsk-Roma', $events[0]->getName());
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventTournaments(): void
    {
        $mock = $this->getMockBuilder(Tournament::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->any())
            ->method('getId')
            ->willReturn(10392)
        ;
        $events = $this->getProvider(null)->getFinishedEvents(100, [$mock]);
        $this->assertCount(8, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventStages(): void
    {
        $mock = $this->getMockBuilder(TournamentStage::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->any())
            ->method('getId')
            ->willReturn(852199)
        ;
        $events = $this->getProvider(null)->getUpcomingEvents(100, [], [$mock]);
        $this->assertCount(8, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventBetweenDate(): void
    {
        $betweenDate = new BetweenDate(
            (new \DateTime())->setDate(2017, 5, 12)->setTime(17, 30, 0),
            (new \DateTime())->setDate(2017, 5, 13)
        );
        $events = $this->getProvider(null)->getFinishedEvents(100, [], [], [], $betweenDate);
        $this->assertCount(1, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetEventBetweenDateNoResult(): void
    {
        $betweenDate = new BetweenDate(
            (new \DateTime())->setDate(2017, 5, 12)->setTime(18, 30, 0),
            (new \DateTime())->setDate(2017, 5, 13)
        );
        $events = $this->getProvider(null)->getFinishedEvents(100, [], [], [], $betweenDate);
        $this->assertCount(0, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetYesterday(): void
    {
        $_ENV['MOCK_TODAY'] = '2017-05-16';
        $events = $this->getProvider(null)->getYesterdayEvents();
        $this->assertCount(0, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetToday(): void
    {
        $_ENV['MOCK_TODAY'] = '2017-05-16';
        $events = $this->getProvider(null)->getTodayEvents();
        $this->assertCount(1, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testGetTomorrow(): void
    {
        $_ENV['MOCK_TODAY'] = '2017-05-16';
        $events = $this->getProvider(null)->getTomorrowEvents();
        $this->assertCount(1, $events);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testMysqlTournamentConfiguration(): void
    {
        $configuration = $this->configuration;
        $configuration->setTournamentTemplates([42]);

        $event = $this->getProvider(null, $configuration)->getEvent(2680944);
        $this->assertInstanceOf(Event::class, $event);
    }

    /**
     * @group mysql
     * @group event
     * @requires extension pdo_mysql
     */
    public function testMysqlSportConfiguration(): void
    {
        $configuration = $this->configuration;
        $configuration->setSports([1]);

        $event = $this->getProvider(null, $configuration)->getEvent(2680944);
        $this->assertInstanceOf(Event::class, $event);
    }
}
