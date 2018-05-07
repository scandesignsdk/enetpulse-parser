<?php
require __DIR__ . '/vendor/autoload.php';

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;

$dsn = '';

$config = new Configuration($dsn);
$generator = new Generator($config);
$event = $generator->getEventProvider()->getEvent(2680952);

// ordinare time - meaning in regular time
// CS: Hvis en kamp ender med 15-15 (så stoppes ordinær tid her, selvom kampen fortsætter i overtid)

/**
 * 1x2 - Ordinare time
 */

echo "\n\n1x2 - Ordinare time\n-------------------\n\n";

echo "// 1x2 - Ordinare time - Home\n\n";

// 1x2 - 3Way (ordinare time) - Home (1)
// CS: Hvis Astralis mod Faze og Astralis vinder - så er 1 tallet opfyldt (ordinær tid kun!) (BEMÆRK at det er fordi Astralis står først)
$multipleOdds = $event->getOrd1x2HomeTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "// 1x2 - Ordinare time - Draw\n\n";

// 1x2 - 3Way (ordinare time) - Draw (x)
// CS: Hvis Astralis mod Faze spiller uafgjort (15-15) - så er X'et opfyldt (ordinær tid kun!)
$multipleOdds = $event->getOrd1x2DrawOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "// 1x2 - Ordinare time - Away\n\n";

// 1x2 - 3Way (ordinare time) - Away (2)
// CS: Hvis Astralis mod Faze og Faze vinder - så er 2 tallet opfyldt (ordinær tid kun!) (BEMÆRK at det er fordi Faze står til sidst)
$multipleOdds = $event->getOrd1x2AwayTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


echo "\n\n1x2 - 1. half\n-------------------\n\n";

echo "// 1x2 - 1. half - Home\n\n";

/**
 * 1x2 - 1. half
 */

// 1x2 - 3Way (1. half) - Home (1)
// CS: Hvis Astralis mod Faze og Astralis vinder 1. halvleg (fx 9-6) - så er 1 tallet opfyldt (BEMÆRK at det er fordi Astralis står først)
$multipleOdds = $event->get1H1x2HomeTeamOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "// 1x2 - 1. half - Draw\n\n";

// 1x2 - 3Way (1. half) - Draw (x)
// CS: Hvis Astralis mod Faze og Astralis spiller uafgjort i 1. halvleg (kan så ikke lade sig gøre i CS - men forstil dig 7-7)
$multipleOdds = $event->get1H1x2DrawOdds();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "// 1x2 - 1. half - Away\n\n";

// 1x2 - 3Way (1. half) - Away (2)
// CS: Hvis Astralis mod Faze og Faze vinder - 1. halvleg (fx 6-9) så er 2 tallet opfyldt (BEMÆRK at det er fordi Faze står til sidst)
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

echo "// Even goals\n\n";


// Get the odds if the match ends with even goals (ordinare time)
// CS: Ender kampen Astralis - Faze med en lige score altså fx 12-16 (28 mål)
$multipleOdds = $event->getOrdEvenGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

echo "// Odd goals\n\n";

// Get the odds if the match ends with odd goals (ordinare time)
// CS: Ender kampen Astralis - Faze med en lige score altså fx 13-16 (29 mål)
$multipleOdds = $event->getOrdOddGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


echo "\n\nO/U (Over / Under goals) (ordinar time)\n-------------------\n\n";

/**
 * O/U (Over / Under goals) - Ordinare time
 */

// Get the odds if goals scored in (ordinare time) is over a number
// CS: Ender kampen Astralis - Faze med en lige score over 18.5 mål (Dparam1 = antal mål) altså fx 3-16 (19 mål)
$multipleOdds = $event->getOrdOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Over: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Get the odds if goals scored in (ordinare time) is under a number
// CS: Ender kampen Astralis - Faze med en lige score UNDER 18.5 mål (Dparam1 = antal mål) altså fx 1-16 (17 mål)
$multipleOdds = $event->getOrdUnderGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Under: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}


echo "\n\nO/U (Over / Under goals) (1. half)\n-------------------\n\n";

/**
 * O/U (Over / Under goals) - 1. half
 */

// Get the odds if goals scored in (1. half) is over a number
// CS: Ender kampen Astralis - Faze med en lige score over 10.5 mål (Dparam1 = antal mål) altså fx 2-9 (11 mål)
$multipleOdds = $event->get1HOverGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Over: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

// Get the odds if goals scored in (1. half) is under a number
// CS: Ender kampen Astralis - Faze med en lige score UNDER 12.5 mål (Dparam1 = antal mål) altså fx 11-4 (15 mål)
$multipleOdds = $event->get1HUnderGoals();
foreach ($multipleOdds as $singleOdds) {
    foreach ($singleOdds->getOffers() as $offer) {
        echo 'Under: ' . $singleOdds->getDparam1() . ' ' . $offer->getProvider()->getName() . ' - ' . $offer->getOdds() . "\n";
    }
}

