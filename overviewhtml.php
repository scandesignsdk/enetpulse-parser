<?php
require __DIR__ . '/vendor/autoload.php';

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;
use SDM\Enetpulse\Model\Event;
use SDM\Enetpulse\Model\Event\Participant;
use SDM\Enetpulse\Model\Odds\BothTeamScoresOdds;
use SDM\Enetpulse\Model\Odds\MatchWinnerHandicapOdds;
use SDM\Enetpulse\Model\Odds\MatchWinnerOdds;
use SDM\Enetpulse\Model\Odds\Offer;
use SDM\Enetpulse\Model\Odds\OverUnderGoalsOdds;
use SDM\Enetpulse\Model\Odds\Provider;
use SDM\Enetpulse\Model\Tournament\TournamentStage;
use SDM\Enetpulse\Utils\BetweenDate;
use SDM\Enetpulse\Utils\Utils;

$dsn = include __DIR__ . '/dsn.php';
$config = new Configuration($dsn);
$generator = new Generator($config);

const DEFAULT_ODDSPROVIDER_ID_FOR_1X2 = 144;

// Calculate date selection
$date = new \DateTime();
if (isset($_GET['date'])) {
    $date = date_create_from_format('Ymd', $_GET['date']);
    if (! $date) {
        $date = new \DateTime();
    }
}

/**
 * Create date selector
 *
 * @param DateTime $currentDate
 * @param int $daysBefore
 * @param int $daysAfter
 *
 * @return string
 */
function createDateSelector(\DateTime $currentDate, int $daysBefore = 2, int $daysAfter = 2) {
    $selected = clone $currentDate;
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queries);
    $currentDate->sub(new \DateInterval('P' . $daysBefore . 'D'));
    $viewDate = 0 - $daysAfter - $daysBefore;
    $output = [];
    do {
        $queries['date'] = $currentDate->format('Ymd');

        $output[] = sprintf(
            '<div class="day--selector %s"><a href="?%s">%s</a></div>',
            $currentDate->format('Ymd') === $selected->format('Ymd') ? 'selected' : '',
            http_build_query($queries),
            $currentDate->format('l d/m')
        );
        $currentDate->add(new \DateInterval('P1D'));
        $viewDate++;
    } while ($viewDate < 1);
    return implode('', $output);
}

$sport = 1;
if (isset($_GET['sport'])) {
    $sport = (int)$_GET['sport'];
}

function createSportSelector(int $selected, array $sports = [1 => 'Fodbold', 2 => 'Tennis']) {
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queries);
    $output = [];
    foreach ($sports as $key => $name) {
        $queries['sport'] = $key;
        $output[] = sprintf(
            '<div class="sport--selector %s"><a href="?%s">%s</a></div>',
            $selected === $key ? 'selected' : '',
            http_build_query($queries),
            $name
        );
    }
    return implode('', $output);
}

$type = 1;
if (isset($_GET['type'])) {
    $type = (int)$_GET['type'];
}

function createMatchTypeSelector(int $selected, array $matchtypes = [1 => 'Alle kampe', 2 => 'Live kampe', 3 => 'Afsluttede kampe']) {
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queries);
    $output = [];
    foreach ($matchtypes as $key => $name) {
        $queries['type'] = $key;
        $output[] = sprintf(
            '<div class="match--selector %s"><a href="?%s">%s</a></div>',
            $selected === $key ? 'selected' : '',
            http_build_query($queries),
            $name
        );
    }
    return implode('', $output);
}

$startTime = clone $date;
$startTime->setTime(0, 0);
$endTime = clone $date;
$endTime->setTime(23, 59, 59);

$betweenDate = new BetweenDate($startTime, $endTime);
switch ($type) {
    default:
    case 1:
        $events = $generator->getEventProvider()->getEvents(500, [], [], [$sport], $betweenDate);
        break;
    case 2:
        $events = $generator->getEventProvider()->getLiveEvents(500, [], [], [$sport], $betweenDate);
        break;
    case 3:
        $events = $generator->getEventProvider()->getFinishedEvents(500, [], [], [$sport], $betweenDate);
        break;
}

$tournaments = Utils::groupEventsByTournament($events, true);

// Template functions
function writeTournamentHeader(TournamentStage $stage): string
{
    return sprintf(
        '%s %s',
        $stage->getCountry(),
        $stage->getName()
    );
}

function write1x2OddsForOverviewMatch(Event $event, $oddsProviderId = DEFAULT_ODDSPROVIDER_ID_FOR_1X2): string
{
    $html = <<<'HTML'
<div class="offer-box offer-box--offer"><a href="%s">%s</a></div>
HTML;

    $writeHtml = function(Offer $offer) use ($html) {
        return sprintf(
            $html,
            $offer->getCouponkey(),
            $offer->getOdds()
        );
    };

    foreach ($event->getOrd1x2Odds() as $matchWinnerOdds) {
        if ($matchWinnerOdds->getProvider()->getId() === $oddsProviderId) {
            $output = [];
            $output[] = $matchWinnerOdds->getHomeOffer() ? $writeHtml($matchWinnerOdds->getHomeOffer()) : writeEmptyOfferBox();
            $output[] = $matchWinnerOdds->getDrawOffer() ? $writeHtml($matchWinnerOdds->getDrawOffer()) : writeEmptyOfferBox();
            $output[] = $matchWinnerOdds->getAwayOffer() ? $writeHtml($matchWinnerOdds->getAwayOffer()) : writeEmptyOfferBox();
            return implode('', $output);
        }
    }

    return '';

}

function writeEmptyOfferBox(): string
{
    return '<div class="offer-box offer-box--offer offer-box--offer_empty"></div>';
}

function writeOddsProvider(Provider $provider): string
{
    return $provider->getName() . ' (' . $provider->getId() . ')';
}

function writeOfferbox(?Offer $offer, string $prefix = null, string $suffix = null)
{
    if ($offer) {
        return '<div class="offer-box offer-box--offer">' . $offer->getOdds() . '</div>';
    }

    return writeEmptyOfferBox();
}

function writeSelector(Event $event, $key, $name): string
{
    return "<div class='odds--selector' data-sub-id='subs_{$key}_{$event->getId()}'>{$name}</div>";
}

function writeSelector_nosub(Event $event, $key, $name): string
{
    return "<div class='odds--selector odds--selector_nosub' data-offers-id='subs_{$key}_{$event->getId()}'>{$name}</div>";
}

function writeSubselector(Event $event, array $subselectors, $key): string
{
    $output = "<div class='odds--subselectors selectors flex-start mb-0' id='subs_{$key}_{$event->getId()}' style='display: none'>";
    foreach ($subselectors as $subkey => $name) {
        $output .= "<div class='odds--subselector' data-offers-id='subs_{$key}_{$event->getId()}_{$subkey}'>{$name}</div>";
    }
    $output .= '</div>';
    return $output;
}

// Odds generators
function createDoubleChance(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    $orddc = $event->getOrdDC();
    if ($orddc) {
        $writeOdds = function(Event $event, MatchWinnerOdds $handicapOdds, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_{$key}_{$event->getId()}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored">1</div>
                        <div class="offer-box offer-box--uncolored">2</div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            $offers[] = '<div class="odds-offer">';
                $offers[] = '<div class="offer--provider">' . writeOddsProvider($handicapOdds->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($handicapOdds->getHomeOffer()) . '
                        ' . writeOfferbox($handicapOdds->getAwayOffer()) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
                $offers[] = '</div>';
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };

        foreach ($orddc as $ord) {
            $key = 'orddc';
            $writeOdds($event, $ord, $key);
        }

        $selectors[] = writeSelector_nosub($event, 'orddc', 'D/C');
    }
}

function createHandicapOdds(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    if ($handicaps = $event->getOrd1x2OddsHandicap()) {
        $writeOdds = function(Event $event, MatchWinnerHandicapOdds $handicapOdds, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_handicap_{$event->getId()}_{$key}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored">1</div>
                        <div class="offer-box offer-box--uncolored">X</div>
                        <div class="offer-box offer-box--uncolored">2</div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            foreach ($handicapOdds->getWinnerOdds() as $matchWinnerOdds) {
                $offers[] = '<div class="odds-offer">';
                $offers[] = '<div class="offer--provider">' . writeOddsProvider($matchWinnerOdds->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($matchWinnerOdds->getHomeOffer()) . '
                        ' . writeOfferbox($matchWinnerOdds->getDrawOffer()) . '
                        ' . writeOfferbox($matchWinnerOdds->getAwayOffer()) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
                $offers[] = '</div>';
            }
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };

        $subs = [];
        foreach ($handicaps as $handicap) {
            $goals = $handicap->calculateHandicap();
            $key = 'handicap_' . str_replace(' ', '_', $goals);
            $subs[$key] = $goals;
            $writeOdds($event, $handicap, $key);
        }

        $selectors[] = writeSelector($event, 'handicap', 'H/C');
        $subselectors[] = writeSubselector($event, $subs, 'handicap');
    }
}

function createOverUnderOdds(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    if ($overunders = $event->getOrdOverUnderAll()) {
        $writeOdds = function(Event $event, OverUnderGoalsOdds $overunder, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_overunder_{$event->getId()}_{$key}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored">Over</div>
                        <div class="offer-box offer-box--uncolored">Under</div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            foreach ($overunder->getOdds() as $offer) {
                $offers[] = '<div class="odds-offer">';
                $offers[] = '<div class="offer--provider">' . writeOddsProvider($offer->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($offer->getOver()) . '
                        ' . writeOfferbox($offer->getUnder()) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
                $offers[] = '</div>';
            }
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };

        $subs = [];
        foreach ($overunders as $overunder) {
            $goals = $overunder->getGoals();
            $key = 'overunder_' . str_replace('.', '_', (string)$goals);
            $subs[$key] = 'O/U ' . $goals . ' mål';
            $writeOdds($event, $overunder, $key);
        }

        $selectors[] = writeSelector($event, 'overunder', 'O/U');
        $subselectors[] = writeSubselector($event, $subs, 'overunder');
    }
}

function create1x2Odds(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    $ord = $event->getOrd1x2Odds();
    $first = $event->get1H1x2Odds();

    if ($ord || $first) {

        $writeOdds = function(Event $event, array $oddsplays, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_1x2_{$event->getId()}_{$key}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored">1</div>
                        <div class="offer-box offer-box--uncolored">X</div>
                        <div class="offer-box offer-box--uncolored">2</div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            foreach ($oddsplays as $matchWinnerOdds) {
                $offers[] = '<div class="odds-offer">';
                $offers[] = '<div class="offer--provider">' . writeOddsProvider($matchWinnerOdds->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($matchWinnerOdds->getHomeOffer()) . '
                        ' . writeOfferbox($matchWinnerOdds->getDrawOffer()) . '
                        ' . writeOfferbox($matchWinnerOdds->getAwayOffer()) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
                $offers[] = '</div>';
            }
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };


        $selectors[] = writeSelector($event, '1x2', '1X2');
        $subs = [];
        if ($ord) {
            $writeOdds($event, $ord, 'fulltime');
            $subs['fulltime'] = 'Fuldtid';
        }

        if ($first) {
            $writeOdds($event, $first, 'firsthalf');
            $subs['firsthalf'] = '1. Halvleg';
        }

        $subselectors[] = writeSubselector($event, $subs, '1x2');
    }
}

function createHalftimeFulltime(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    $htft = $event->getOrdHalftimeFulltime();
    if ($htft) {
        /**
         * @param Event $event
         * @param Offer[] $oddsplays
         * @param string $key
         */
        $writeOdds = function(Event $event, array $oddsplays, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_htft_{$event->getId()}_{$key}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored"></div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            foreach ($oddsplays as $offer) {
                $offers[] = '<div class="odds-offer">';
                $offers[] = '<div class="offer--provider">' . writeOddsProvider($offer->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($offer) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
                $offers[] = '</div>';
            }
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };

        $selectors[] = writeSelector($event, 'htft', 'HT/FT');
        $subs = [];
        foreach ($htft as $halftimeFullTime) {
            $halfTime = 'X';
            $fullTime = 'X';
            if (null !== $halftimeFullTime->getHalftimeParticipant()) {
                $halfTime = $halftimeFullTime->getHalftimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
            }
            if (null !== $halftimeFullTime->getFullTimeParticipant()) {
                $fullTime = $halftimeFullTime->getFullTimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
            }

            $key = $halfTime . '_' . $fullTime;
            $subs[$key] = $halfTime . ' / ' . $fullTime;
            $writeOdds($event, $halftimeFullTime->getOffers(), $key);
        }

        $subselectors[] = writeSubselector($event, $subs, 'htft');
    }
}

function createBHS(Event $event, array &$selectors, array &$subselectors, array &$odds): void
{
    if ($bhs = $event->getOrdBothTeamScores()) {
        $writeOdds = function(Event $event, BothTeamScoresOdds $handicapOdds, string $key) use (&$odds) {
            $offers[] = "<div class='odds-offers' id='subs_{$key}_{$event->getId()}' style='display: none'>";
            $offers[] = '<div class="odds-offer odds-offer--header">
                    <div class="offer--provider">Bookmaker</div>
                    <div class="match--offers">
                        <div class="offer-box offer-box--uncolored">Yes</div>
                        <div class="offer-box offer-box--uncolored">No</div>
                    </div>
                    <div class="offer--empty"></div>
                </div>
            ';
            $offers[] = '<div class="odds-offer">';
            $offers[] = '<div class="offer--provider">' . writeOddsProvider($handicapOdds->getProvider()) . '</div>
                    <div class="match--offers">
                        ' . writeOfferbox($handicapOdds->getYesOffer()) . '
                        ' . writeOfferbox($handicapOdds->getNoOffer()) . '
                    </div>
                    <div class="offer--empty"></div>
                ';
            $offers[] = '</div>';
            $offers[] = '</div>';
            $odds[] = implode('', $offers);
        };

        foreach ($bhs as $bh) {
            $key = 'bhs';
            $writeOdds($event, $bh, $key);
        }

        $selectors[] = writeSelector_nosub($event, 'bhs', 'BHS');
    }
}

?><!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hello, world!</title>
    <style>
        html, body {
            background-color: #cccccc;
            font-family: Arial, serif;
        }

        /** Basic stuff **/
        .mt-1 {
            margin-top: 10px;
        }
        .mb-0 {
            margin-bottom: 0 !important;
        }

        /** Flex settings **/
        .flex-start {
            display: flex;
            justify-content: flex-start;
        }

        /** Selectors **/
        .selectors {
            margin-bottom: 10px;
        }
        .selectors a,
        .selectors a:hover {
            text-decoration: none;
            color: black;
        }
        .selectors > div {
            text-align: center;
            background-color: #fff;
            align-self: center;
            padding: 5px 30px;
            margin: 0 1px;
        }
        .selectors > div.selected {
            background-color: cornflowerblue;
            color: white;
        }
        .selectors div.selected a,
        .selectors div.selected a:hover {
            color: white;
        }
        .day--selector {
            flex: 1 100%;
        }
        .day--selector:last-child {
            flex: 1 auto;
        }

        /** Tournament/Banner containers **/
        .tournaments-container {
            display: flex;
        }
        .tournaments {
            flex: 1 100%;
            margin-right: 20px;
        }
        .banners {
            width: 350px;
        }

        /** Tournament overview **/
        .tournament {}
        .tournament-header {
            display: flex;
            background-color: cornflowerblue;
            padding: 10px;
            color: white;
            cursor: pointer;
        }
        .tournament-header > div:first-child {
            flex: 1 100%;
        }
        .tournament-header > div:last-child {
            width: 20px;
        }
        .tournament-header > div:last-child::after {
            content: "+";
        }
        .opened .tournament-header > div:last-child::after {
            content: "-";
        }

        /** Offer box **/
        .offer-box {
            width: 50px;
            padding: 5px 10px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background-color: green;
            margin-left: 1px;
        }
        .offer-box--offer_empty {
            background-color: transparent !important;
        }
        .offer-box--uncolored {
            background-color: initial;
            color: gray;
            font-size: 1rem;
        }
        .offer-box--offer {
            color: white;
            font-size: 1rem;
        }
        .offer-box--offer a,
        .offer-box--offer a:hover {
            text-decoration: none;
            color: white;
        }

        /** Tournament matches **/
        .matches {

        }
        .match-container {
            cursor: pointer;
            display: flex;
            margin-bottom: 1px;
            background-color: gray;
            align-items: center;
        }
        .match-container > * {
            padding: 5px 10px;
            flex: 1 1 100%;
        }
        .match.opened .match-container {
            background-color: white;
        }
        .match.opened .match--time {
            border-left:2px solid red;
        }
        .match--time {
            flex: 1 5%;
        }
        .match--participants {}
        .match--stats {
            flex: 1 5%;
        }
        .match--offers {
            flex: 1 0 250px;
            display: flex;
            justify-content: center;
        }
        .match--moreodds {
            flex: 0 0 75px;
            text-align: center;
        }

        /** Odds **/
        .odds-container {margin-bottom:20px;}
        .odds--selector {cursor: pointer}
        .odds--subselectors {cursor: pointer}

        /** Offer **/
        .odds-offer {
            display: flex;
            justify-content: flex-start;
            background-color: white;
            align-items: center;
            margin-bottom:1px;
        }
        .odds-offer > div {
            padding: 5px 10px;
        }
        .offer--provider {
            flex: 1 1 100%;
        }
        .offer--empty {
            flex: 0 0 75px;
        }
    </style>
</head>
<body>

<section>
    <div class="day-selectors selectors flex-start">
        <?php echo createDateSelector($date); ?>
        <div class="day--selector"><img src="https://dummyimage.com/15x15/cccccc/000.png"></div>
    </div>
    <div class="sport-selectors selectors flex-start">
        <?php echo createSportSelector($sport); ?>
    </div>
    <div class="match-selectors selectors flex-start">
        <?php echo createMatchTypeSelector($type); ?>
    </div>

    <?php
    $selectors = [];
    $subselectors = [];
    $oddsData = [];
    ?>
    <section class="tournaments-container">
        <section class="tournaments">
            <?php foreach ($tournaments as $tournament): ?>
                <article class="tournament">
                    <div class="tournament-header">
                        <div><?php echo writeTournamentHeader($tournament->getTournamentStage()); ?></div>
                        <div class="openclose"></div>
                    </div>
                    <div class="matches" style="display: none">
                        <?php foreach ($tournament->getEvents() as $event): ?>
                            <?php create1x2Odds($event, $selectors, $subselectors, $oddsData); ?>
                            <?php createOverUnderOdds($event, $selectors, $subselectors, $oddsData); ?>
                            <?php createHandicapOdds($event, $selectors, $subselectors, $oddsData); ?>
                            <?php createDoubleChance($event, $selectors, $subselectors, $oddsData); ?>
                            <?php createHalftimeFulltime($event, $selectors, $subselectors, $oddsData); ?>
                            <?php createBHS($event, $selectors, $subselectors, $oddsData); ?>
                            <div class="match">
                                <div class="match-container">
                                    <div class="match--time"><?php echo $event->getStartDate()->format('H:i'); ?></div>
                                    <div class="match--participants"><?php echo implode(' - ', array_map(function(Participant $participant) { return $participant->getName(); }, $event->getParticipants())); ?></div>
                                    <div class="match--stats"><img src="https://dummyimage.com/25x25/000/fff.png"></div>
                                    <div class="match--offers">
                                        <?php echo write1x2OddsForOverviewMatch($event); ?>
                                    </div>
                                    <div class="match--moreodds">+<?php echo count($event->getOdds()); ?></div>
                                </div>
                                <div class="odds-container mt-1" style="display: none">
                                    <div class="odds--selectors flex-start selectors mb-0">
                                        <?php echo implode('', $selectors); ?>
                                    </div>
                                    <?php echo implode('', $subselectors); ?>
                                    <?php echo implode('', $oddsData); ?>
                                </div>
                            </div>
                                <?php $selectors = $subselectors = $oddsData = []; ?>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
        <section class="banners">
            <div class="banner">
                <img src="<?php echo 'https://dummyimage.com/300x250/000/fff.png'; ?>">
            </div>
            <div class="banner">
                <img src="<?php echo 'https://dummyimage.com/300x250/000/fff.png'; ?>">
            </div>
            <div class="banner">
                <img src="<?php echo 'https://dummyimage.com/300x250/000/fff.png'; ?>">
            </div>
        </section>
    </section>
</section>

<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
<script>
    function resetOddsSelectors($elm, dontHideOddsSelector) {
        let $closest = $elm.closest('.odds-container');
        if (! dontHideOddsSelector) {
            let $selectors = $closest.find('.odds--selector');
            $selectors.removeClass('selected');
            let $subs = $closest.find('.odds--subselectors');
            $subs.hide().find('div').removeClass('selected');
        }

        let $offers = $closest.find('.odds-offers');
        $offers.hide();
    }

    // Opening a tournament
    $('.tournament-header').click(function() {
        let $this = $(this);
        let $tournament = $this.closest('.tournament');
        let $matches = $tournament.find('.matches');
        if ($matches.is(':visible')) {
            $matches.hide();
            $tournament.removeClass('opened');
        } else {
            $matches.show();
            $tournament.addClass('opened');
        }
    });

    // Opening a match odds
    $('.match-container').click(function() {
        let $this = $(this);
        let $oddscontainer = $this.parent().find('.odds-container');
        if ($oddscontainer.is(':visible')) {
            $this.parent().removeClass('opened');
            $oddscontainer.hide();
        } else {
            $this.parent().addClass('opened');
            $oddscontainer.show();
        }
    });

    // Opening a odds selector
    $('.odds--selector:not(.odds--selector_nosub)').click(function() {
        let $this = $(this);
        let $sub = $('#' + $this.data('sub-id'));
        resetOddsSelectors($this);
        $this.addClass('selected');
        $sub.show();
    });

    // Opening a odds selector with no sub selector
    $('.odds--selector_nosub').click(function() {
        let $this = $(this);
        let $offer = $('#' + $this.data('offers-id'));
        resetOddsSelectors($this);
        $this.addClass('selected');
        $offer.show();
    });

    // Opening a sub selector
    $('.odds--subselector').click(function() {
        let $this = $(this);
        let $offer = $('#' + $this.data('offers-id'));
        let $subs = $this.closest('.odds-container').find('.odds--subselectors');
        $subs.find('div').removeClass('selected');
        resetOddsSelectors($this, true);
        $this.addClass('selected');
        $offer.show();
    });
</script>
</body>
</html>
