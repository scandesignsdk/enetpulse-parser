<?php
require __DIR__ . '/vendor/autoload.php';

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;
use SDM\Enetpulse\Model\Event\Participant;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

$dsn = include __DIR__ . '/dsn.php';

$config = new Configuration($dsn);
$generator = new Generator($config);

/**
 * Table standings
 */
echo "\n\nTable standings\n-------------------\n\n";

$output = new BufferedOutput();
// Total for all matches
$output->writeln('// Total for all matches');
$table = new Table($output);
$table->setHeaders(['Team', 'Rank', 'Matches', 'Won', 'Draws', 'Lost', 'Goals', 'Points']);
$standing = $generator->getStandingProvider()->getStandingFromTournamentStageId(849851);
foreach ($standing->getTotalStandings() as $stand) {
    $table->addRow([
        $stand->getParticipant()->getName(),
        $stand->getData()->getRank(),
        $stand->getData()->getPlayed(),
        $stand->getData()->getWins(),
        $stand->getData()->getDraws(),
        $stand->getData()->getDefeits(),
        $stand->getData()->getGoalsFor() . '-' . $stand->getData()->getGoalsAgainst(),
        $stand->getData()->getPoints(),
    ]);
}
$table->render();

// Total for home matches
$output->writeln('// Total for home matches');
$table = new Table($output);
$table->setHeaders(['Team', 'Rank', 'Matches', 'Won', 'Draws', 'Lost', 'Goals', 'Points']);
$standing = $generator->getStandingProvider()->getStandingFromTournamentStageId(849851);
foreach ($standing->getHomeStandings() as $stand) {
    $table->addRow([
        $stand->getParticipant()->getName(),
        $stand->getData()->getRank(),
        $stand->getData()->getPlayed(),
        $stand->getData()->getWins(),
        $stand->getData()->getDraws(),
        $stand->getData()->getDefeits(),
        $stand->getData()->getGoalsFor() . '-' . $stand->getData()->getGoalsAgainst(),
        $stand->getData()->getPoints(),
    ]);
}
$table->render();

// Total for away matches
$output->writeln('// Total for away matches');
$table = new Table($output);
$table->setHeaders(['Team', 'Rank', 'Matches', 'Won', 'Draws', 'Lost', 'Goals', 'Points']);
$standing = $generator->getStandingProvider()->getStandingFromTournamentStageId(849851);
foreach ($standing->getAwayStandings() as $stand) {
    $table->addRow([
        $stand->getParticipant()->getName(),
        $stand->getData()->getRank(),
        $stand->getData()->getPlayed(),
        $stand->getData()->getWins(),
        $stand->getData()->getDraws(),
        $stand->getData()->getDefeits(),
        $stand->getData()->getGoalsFor() . '-' . $stand->getData()->getGoalsAgainst(),
        $stand->getData()->getPoints(),
    ]);
}
$table->render();

echo $output->fetch();


$event = $generator->getEventProvider()->getEvent(2680952);

echo "----- EVENT -----\n";
echo implode(' - ', array_map(function(Participant $part) { return $part->getName(); }, $event->getParticipants()));
echo "\n";
echo $event->getStartDate()->format('Y-m-d H:i');
echo "\n=================================";

// ordinare time - meaning in regular time
// CS: Hvis en kamp ender med 15-15 (så stoppes ordinær tid her, selvom kampen fortsætter i overtid)

/**
 * 1x2 - Ordinare time
 */
echo "\n\n1x2 - Ordinare time\n-------------------\n\n";

// 1x2 - 3Way (ordinare time) - Home (1)
// CS: Hvis Astralis mod Faze og Astralis vinder - så er 1 tallet opfyldt (ordinær tid kun!) (BEMÆRK at det er fordi Astralis står først)
echo "// 1x2 - Ordinare time - Home\n\n";
$multipleOdds = $event->getOrd1x2HomeTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 - 3Way (ordinare time) - Draw (x)
// CS: Hvis Astralis mod Faze spiller uafgjort (15-15) - så er X'et opfyldt (ordinær tid kun!)
echo "// 1x2 - Ordinare time - Draw\n\n";
$multipleOdds = $event->getOrd1x2DrawOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 - 3Way (ordinare time) - Away (2)
// CS: Hvis Astralis mod Faze og Faze vinder - så er 2 tallet opfyldt (ordinær tid kun!) (BEMÆRK at det er fordi Faze står til sidst)
echo "// 1x2 - Ordinare time - Away\n\n";
$multipleOdds = $event->getOrd1x2AwayTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


/**
 * 1x2 - 1. half
 */
echo "\n\n1x2 - 1. half\n-------------------\n\n";

// 1x2 - 3Way (1. half) - Home (1)
// CS: Hvis Astralis mod Faze og Astralis vinder 1. halvleg (fx 9-6) - så er 1 tallet opfyldt (BEMÆRK at det er fordi Astralis står først)
echo "// 1x2 - 1. half - Home\n\n";
$multipleOdds = $event->get1H1x2HomeTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 - 3Way (1. half) - Draw (x)
// CS: Hvis Astralis mod Faze og Astralis spiller uafgjort i 1. halvleg (kan så ikke lade sig gøre i CS - men forstil dig 7-7)
echo "// 1x2 - 1. half - Draw\n\n";
$multipleOdds = $event->get1H1x2DrawOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 - 3Way (1. half) - Away (2)
// CS: Hvis Astralis mod Faze og Faze vinder - 1. halvleg (fx 6-9) så er 2 tallet opfyldt (BEMÆRK at det er fordi Faze står til sidst)
echo "// 1x2 - 1. half - Away\n\n";
$multipleOdds = $event->get1H1x2DrawOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * O/E (Ord / Even goals)
 */
echo "\n\nO/E (odd/even goals)\n-------------------\n\n";

// Get the odds if the match ends with even goals (ordinare time)
// CS: Ender kampen Astralis - Faze med en lige score altså fx 12-16 (28 mål)
echo "// Even goals\n\n";
$multipleOdds = $event->getOrdEvenGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Get the odds if the match ends with odd goals (ordinare time)
// CS: Ender kampen Astralis - Faze med en lige score altså fx 13-16 (29 mål)
echo "// Odd goals\n\n";
$multipleOdds = $event->getOrdOddGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


/**
 * O/U (Over / Under goals) - Ordinare time
 */
echo "\n\nO/U (Over / Under goals) (ordinar time)\n-------------------\n\n";

// Get the odds if goals scored in (ordinare time) is over a number
// CS: Ender kampen Astralis - Faze med en lige score over 18.5 mål (Dparam1 = antal mål) altså fx 3-16 (19 mål)
echo "// Over goals\n\n";
$multipleOdds = $event->getOrdOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Over: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Get the odds if goals scored in (ordinare time) is under a number
// CS: Ender kampen Astralis - Faze med en lige score UNDER 18.5 mål (Dparam1 = antal mål) altså fx 1-16 (17 mål)
echo "// Under goals\n\n";
$multipleOdds = $event->getOrdUnderGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Under: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


/**
 * O/U (Over / Under goals) - 1. half
 */
echo "\n\nO/U (Over / Under goals) (1. half)\n-------------------\n\n";

// Get the odds if goals scored in (1. half) is over a number
// CS: Ender kampen Astralis - Faze med en lige score over 10.5 mål (Dparam1 = antal mål) altså fx 2-9 (11 mål)
echo "// Over goals\n";
$multipleOdds = $event->get1HOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Over: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Get the odds if goals scored in (1. half) is under a number
// CS: Ender kampen Astralis - Faze med en lige score UNDER 12.5 mål (Dparam1 = antal mål) altså fx 11-4 (15 mål)
echo "\n// Under goals\n";
$multipleOdds = $event->get1HUnderGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Under: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * AH (Asian Handicap)
 */

// This will decrease or increase the "start" goals for both teams
// This is a special one, because we need the score - so it will
// CS: Forstil dig at Astralis - Faze og man så siger at Astralis starter med minus mål (fx -2.50) og kampen ender 16-14, så har Faze "vundet" dette bet fordi Astralis skal vinde kampen med mere end 2.50 mål
// ABANDON FOR THE TIME BEING!!
//$multipleHandicaps = $event->getOrdAH();
//foreach ($multipleHandicaps as $handicap) {
//    dump($handicap);
//}


/**
 * 1x2 with handicap
 */
echo "\n\n1x2 with handicap (ordinar time)\n-------------------\n\n";

// Same as the other 1x2 - but this is when a team starts with a either minus or plus goals
// This is a special one, because we need some handicap

// 1x2 Handicap - Ordinare time - Home
echo "\n\n// 1x2 Handicap - Ordinare time - Home\n\n";
$multipleHandicaps = $event->getOrd1x2HomeTeamOddsHandicap();
foreach ($multipleHandicaps as $handicap) {
    echo "\nHandicap: " .  $handicap->getHandicap() . ' Team - ' . $handicap->getParticipant()->getName() . "\n----------\n";
    foreach ($handicap->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 Handicap - Ordinare time - Draw
echo "\n\n// 1x2 Handicap - Ordinare time - Draw\n\n";
$multipleHandicaps = $event->getOrd1x2HomeTeamOddsHandicap();
foreach ($multipleHandicaps as $handicap) {
    echo "\nHandicap: " .  $handicap->getHandicap() . ' Team - ' . $handicap->getParticipant()->getName() . "\n----------\n";
    foreach ($handicap->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// 1x2 Handicap - Ordinare time - Away
echo "\n\n// 1x2 Handicap - Ordinare time - Away\n\n";
$multipleHandicaps = $event->getOrd1x2AwayTeamOddsHandicap();
foreach ($multipleHandicaps as $handicap) {
    echo "\nHandicap: " .  $handicap->getHandicap() . ' Team - ' . $handicap->getParticipant()->getName() . "\n----------\n";
    foreach ($handicap->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * DC (Double Chance)
 */
// CS: Her kan man spille på om et hold enten vinder eller spiller uafgjort (dobbelt chance)
echo "\n\nDC (Double chance) (ordinar time)\n-------------------\n\n";

// Win/Draw - Ordinare time - Home
echo "\n// Home team\n";
$multipleOdds = $event->get1HOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Win/Draw - Ordinare time - Away
echo "\n// Away team\n";
$multipleOdds = $event->get1HOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * hf_tf (Halftime / Fulltime)
 */
echo "\n\nhf_tf (Halftime / Fulltime)\n-------------------\n\n";
// CS: Her kan man spille på hvem der vinder 1. halvleg og hvem der vinder ved fuldtid
// Fx Astralis - Faze 9-6 (Astralis vandt første halvleg) - Kampen ender 12-16 (Faze vandt fuldtid)
// BEMÆRK - at hvis et af holdene er NULL så er det uafjort
// Fx
// Astralis - Faze 9-6 - men kampen ender 15-15 - så vil Astralis holdet være halvlegs holdet - men der vil være NULL i fuldtids holdet
$multipleHFTF = $event->getOrdHalftimeFulltime();
foreach ($multipleHFTF as $singleHFTF) {
    $halfTime = 'X';
    $fullTime = 'X';
    if (null !== $singleHFTF->getHalftimeParticipant()) {
        $halfTime = $singleHFTF->getHalftimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
    }
    if (null !== $singleHFTF->getFullTimeParticipant()) {
        $fullTime = $singleHFTF->getFullTimeParticipant()->getId() === $event->getParticipants()[0]->getId() ? 1 : 2;
    }

    echo $halfTime . ' / ' . $fullTime . "\n";
    foreach ($singleHFTF->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * bts (Both team scores)
 */
echo "\n\nbts (Both team scores)\n-------------------\n\n";

echo "\n// Yes\n";
$multipleOdds = $event->getOrdBothTeamScoresYes();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "\n// No\n";
$multipleOdds = $event->getOrdBothTeamScoresNo();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

/**
 * Upcoming matches for a participant (not started matches)
 */
echo "\n\nUpcoming matches for a participant (not started matches)\n-------------------\n\n";
// Can be either a integer or a participant object
$events = $generator->getEventProvider()->getUpcomingMatchesParticipant($event->getParticipants()[0], 5);
foreach ($events as $upcomingEvent) {
    echo $upcomingEvent->getStartDate()->format('Y-m-d H:i') . ' - ' . implode(' - ', array_map(function(Participant $participant) { return $participant->getName(); }, $upcomingEvent->getParticipants())) . "\n";
}

/**
 * Latest matches for a participant (finished matches)
 */
echo "\n\nLatest matches for a participant (finished matches)\n-------------------\n\n";
// Can be either a integer or a participant object
$events = $generator->getEventProvider()->getLatestMatchesParticipant($event->getParticipants()[0], 5);
foreach ($events as $latestEvent) {
    echo $latestEvent->getStartDate()->format('Y-m-d H:i') . ' - ' . implode(' - ', array_map(function(Participant $participant) { return $participant->getName(); }, $latestEvent->getParticipants())) . "\n";
}

/**
 * Latest matches between multiple participants (finished matches)
 */
echo "\n\nLatest matches between multiple participants (finished matches)\n-------------------\n\n";
// Can be either a array of integers or an array of participant objects
$events = $generator->getEventProvider()->getLatestMatchesBetween($event->getParticipants(), 5);
foreach ($events as $latestBetween) {
    echo $latestBetween->getStartDate()->format('Y-m-d H:i') . ' - ' . implode(' - ', array_map(function(Participant $participant) { return $participant->getName(); }, $latestBetween->getParticipants())) . "\n";
}
