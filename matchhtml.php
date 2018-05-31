<?php

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Model\Odds\Provider;

require __DIR__ . '/vendor/autoload.php';

$dsn = include __DIR__ . '/dsn.php';
$config = new Configuration($dsn);
$generator = new Generator($config);

$event = $generator->getEventProvider()->getEvent(2059521);

/**
 * Function to translate our text
 *
 * @param string $text
 * @return string
 */
function __($text)
{
    return $text;
}

/**
 * Function to write accordion headers, a bit easier
 *
 * @param string $title
 * @return string
 */
function headerString($title)
{
    $div = '<div class="header flex-space-between">';
    $div .= sprintf('<div class="title">%s</div>', $title);
    $div .= '<div class="openclose closed"></div>';
    $div .= '</div>';
    return $div;
}

/**
 * Display a odds provider
 *
 * @param Provider $provider
 *
 * @return string
 */
function showOddsProvider(Provider $provider)
{
    return sprintf(
        '<div class="provider"><img src="%s" alt="%s"><span>%s</span></div>',
        'https://dummyimage.com/' . random_int(75, 200) . 'x' . random_int(75, 200) . '/000/fff.png',
        $provider->getName(),
        $provider->getName()
    );
}

/**
 * Display a offer
 * Its possible to add prefix or suffix or both
 *
 * @param Odds\Offer|null $offer
 * @param string|null $prefix
 * @param string|null $suffix
 *
 * @return string
 */
function showOffer($offer, $prefix = null, $suffix = null)
{
    if ($offer === null) {
        return '';
    }

    return sprintf(
        '
            <div class="offer">
                <a href="%s">
                    <span class="offerdisplay">
                        <span class="offerdisplay--offer offerdisplay--prefix">%s</span>
                        <span class="offerdisplay--offer">%s</span>
                        <span class="offerdisplay--offer offerdisplay--suffix">%s</span>
                    </span>
                </a>
            </div>',
        $offer->getProvider()->getUrl() . '?' . $offer->getCouponkey(),
        $prefix,
        $offer->getOdds(),
        $suffix
    );
}

/**
 * Display the header for halftime/fulltime
 *
 * @param Event $event
 * @param Odds\HalftimeFullTime $halftimeFullTime
 *
 * @return string
 */
function showHalftimeHeader(Event $event, Odds\HalftimeFullTime $halftimeFullTime) {
    $halfTime = 'X';
    $fullTime = 'X';
    if (null !== $halftimeFullTime->getHalftimeParticipant()) {
        $halfTime = $halftimeFullTime->getHalftimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
    }
    if (null !== $halftimeFullTime->getFullTimeParticipant()) {
        $fullTime = $halftimeFullTime->getFullTimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
    }

    return '<div class="halftimefulltime--header">
            <span class="halftimefulltime--value">' . $halfTime . ' / ' . $fullTime . '</span>
        </div>';
}

/**
 * Calculate the handicap score
 *
 * @param Odds\Handicap $handicap
 *
 * @return string
 */
function calculateHandicap(Odds\Handicap $handicap)
{
    $homeScore = 0;
    $awayScore = 0;

    if ($handicap->getHandicap() > 0) {
        $homeScore = $handicap->getHandicap();
    } elseif ($handicap->getHandicap() < 0) {
        $awayScore = abs($handicap->getHandicap());
    }

    return $homeScore . ' - ' . $awayScore;
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hello, world!</title>

    <style>
        html, body {
            font-family: Arial, serif;
        }

        #accordion {

        }

        .card {
            margin-bottom: 10px;
        }

        .card .header {
            cursor: pointer;
            color: white;
            background-color: blue;
            padding: 20px;
        }

        .flex-space-between {
            display: flex;
            justify-content: space-between;
        }

        .flex-center {
            display: flex;
            justify-content: center;
        }

        .collapse.hide {
            display: none;
        }
        .collapse--header {
            background-color: yellow;
            font-size: 2rem;
            padding: 0 10px;
        }

        .openclose.opened:after {
            content: "-";
        }

        .openclose.closed:after {
            content: "+";
        }

        .offers {
            margin-bottom: 10px;
        }

        .offers:nth-child(even) {
            background-color: gray;
        }

        .offer {
            background-color: green;
            margin-left: 5px;
        }

        .offer a,
        .offer a:hover {
            text-decoration: none;
        }

        .offerdisplay {
            height: 75px;
            width: 75px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .offerdisplay--offer {
            color: white;
            font-size: 1.3rem;
        }

        .offerdisplay--prefix {
            font-size: 0.7rem;
        }

        .offerdisplay--suffix {
            font-size: 0.7rem;
        }

        .provider {
            display: flex;
            align-items: center;
            height: 75px;
        }

        .provider > img {
            max-height: 75px;
            max-width: 75px;
        }

        .provider > span {
            line-height: 75px;
        }
    </style>
</head>
<body>

<div id="accordion">
    <!-- Kamp vinder -->
    <div class="card">
        <?php echo headerString(__('Kampvinder')); ?>
        <div class="collapse hide">
            <?php foreach ($event->getOrd1x2Odds() as $matchWinnerOdds): ?>
                <div class="matchwinner offers flex-space-between">
                    <?php echo showOddsProvider($matchWinnerOdds->getProvider()); ?>
                    <div class="odds flex-center">
                        <div class="home">
                            <?php echo showOffer($matchWinnerOdds->getHomeOffer(), 1); ?>
                        </div>
                        <div class="draw">
                            <?php echo showOffer($matchWinnerOdds->getDrawOffer(), 'X'); ?>
                        </div>
                        <div class="away">
                            <?php echo showOffer($matchWinnerOdds->getAwayOffer(), 2); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Hvem vinder 1. halvleg -->
    <div class="card">
        <?php echo headerString(__('Hvem vinder 1. halvleg')); ?>
        <div class="collapse hide">
            <?php foreach ($event->get1H1x2Odds() as $firsthalfwinnerOdds): ?>
                <div class="winner1half offers flex-space-between">
                    <?php echo showOddsProvider($firsthalfwinnerOdds->getProvider()); ?>
                    <div class="odds flex-center">
                        <div class="home">
                            <?php echo showOffer($firsthalfwinnerOdds->getHomeOffer(), 1); ?>
                        </div>
                        <div class="draw">
                            <?php echo showOffer($firsthalfwinnerOdds->getDrawOffer(), 'X'); ?>
                        </div>
                        <div class="away">
                            <?php echo showOffer($firsthalfwinnerOdds->getAwayOffer(), 2); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Handicap -->
    <div class="card">
        <?php echo headerString(__('Handicap')); ?>
        <div class="collapse hide">
            <?php foreach ($event->getOrd1x2OddsHandicap() as $handicap): ?>
                <div class="collapse--header">
                    <?php echo calculateHandicap($handicap->getHandicap()); ?>
                </div>
                <?php foreach ($handicap->getWinnerOdds() as $handicapOdds): ?>
                    <div class="handicap offers flex-space-between">
                        <?php echo showOddsProvider($handicapOdds->getProvider()); ?>
                        <div class="odds flex-center">
                            <div class="home">
                                <?php echo showOffer($handicapOdds->getHomeOffer(), 1); ?>
                            </div>
                            <div class="draw">
                                <?php echo showOffer($handicapOdds->getDrawOffer(), 'X'); ?>
                            </div>
                            <div class="away">
                                <?php echo showOffer($handicapOdds->getAwayOffer(), 2); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Antal mål u/o 2,5 -->
    <div class="card">
        <?php $over25goals = $event->getOrdOverUnderGoals(2.5); ?>
        <?php echo headerString(__('Antal mål u/o ' . $over25goals->getGoals())); ?>
        <div class="collapse hide">
            <?php foreach ($over25goals->getOdds() as $odds): ?>
                <div class="overundergoals offers flex-space-between">
                    <?php echo showOddsProvider($odds->getProvider()); ?>
                    <div class="odds flex-center">
                        <div class="over">
                            <?php echo showOffer($odds->getOver(), 'Over'); ?>
                        </div>
                        <div class="under">
                            <?php echo showOffer($odds->getUnder(), 'Under'); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Antal mål u/o resten -->
    <div class="card">
        <?php echo headerString('Antal mål u/o'); ?>
        <div class="collapse hide">
            <?php foreach ($event->getOrdOverUnderAll(2.5) as $overunder): ?>
                <div class="collapse--header">
                    <?php echo $overunder->getGoals(); ?>
                </div>
                <?php foreach ($overunder->getOdds() as $odds): ?>
                    <div class="overundergoals offers flex-space-between">
                        <?php echo showOddsProvider($odds->getProvider()); ?>
                        <div class="odds flex-center">
                            <div class="over">
                                <?php echo showOffer($odds->getOver(), 'Over'); ?>
                            </div>
                            <div class="under">
                                <?php echo showOffer($odds->getUnder(), 'Under'); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Antal mål 1. halvleg u/o 2,5 -->
    <div class="card">
        <?php $over25goals = $event->get1HOverUnderGoals(2.5); ?>
        <?php echo headerString(__('Antal mål 1. halvleg u/o ' . $over25goals->getGoals())); ?>
        <div class="collapse hide">
            <?php foreach ($over25goals->getOdds() as $odds): ?>
                <div class="overundergoals offers flex-space-between">
                    <?php echo showOddsProvider($odds->getProvider()); ?>
                    <div class="odds flex-center">
                        <div class="over">
                            <?php echo showOffer($odds->getOver(), 'Over'); ?>
                        </div>
                        <div class="under">
                            <?php echo showOffer($odds->getUnder(), 'Under'); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Antal mål 1. halvleg u/o resten -->
    <div class="card">
        <?php echo headerString('Antal mål 1. halvleg u/o'); ?>
        <div class="collapse hide">
            <?php foreach ($event->get1HOverUnderAll(2.5) as $overunder): ?>
                <div class="collapse--header">
                    <?php echo $overunder->getGoals(); ?>
                </div>
                <?php foreach ($overunder->getOdds() as $odds): ?>
                    <div class="overundergoals offers flex-space-between">
                        <?php echo showOddsProvider($odds->getProvider()); ?>
                        <div class="odds flex-center">
                            <div class="over">
                                <?php echo showOffer($odds->getOver(), 'Over'); ?>
                            </div>
                            <div class="under">
                                <?php echo showOffer($odds->getUnder(), 'Under'); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Halvleg/Fuldtid -->
    <div class="card">
        <?php echo headerString(__('Halvleg/Fuldtid')); ?>
        <div class="collapse hide">
            <?php foreach ($event->getOrdHalftimeFulltime() as $halftimeFullTime): ?>
                <div class="collapse--header">
                    <?php echo showHalftimeHeader($event, $halftimeFullTime); ?>
                </div>
                <?php foreach ($halftimeFullTime->getOffers() as $offer): ?>
                    <div class="halftimefulltime offers flex-space-between">
                        <?php echo showOddsProvider($offer->getProvider()); ?>
                        <div class="odds flex-center">
                            <?php echo showOffer($offer); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Dobbeltchance -->
    <div class="card">
        <?php echo headerString(__('Dobbeltchance')); ?>
        <div class="collapse hide">
            <?php foreach ($event->getOrdDC() as $doublechance): ?>
                <div class="doublechance offers flex-space-between">
                    <?php echo showOddsProvider($doublechance->getProvider()); ?>
                    <div class="odds flex-center">
                        <div class="home">
                            <?php echo showOffer($doublechance->getHomeOffer(), 1); ?>
                        </div>
                        <div class="away">
                            <?php echo showOffer($doublechance->getAwayOffer(), 2); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
<script>
    $('.card > .header').click(function () {
        let elm = $(this).find('.openclose');
        if (elm.hasClass('opened')) {
            elm
                .removeClass('opened')
                .addClass('closed')
                .closest('div.card').find('div.collapse').hide()
            ;
        } else {
            elm
                .removeClass('closed')
                .addClass('opened')
                .closest('div.card').find('div.collapse').show()
            ;
        }
    })
</script>

</body>
</html>
