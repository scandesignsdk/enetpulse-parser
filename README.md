Enetpulse Parser
----------------

[![Build Status](https://travis-ci.org/scandesignsdk/enetpulse-parser.svg?branch=master)](https://travis-ci.org/scandesignsdk/enetpulse-parser)
[![codecov](https://codecov.io/gh/scandesignsdk/enetpulse-parser/branch/master/graph/badge.svg)](https://codecov.io/gh/scandesignsdk/enetpulse-parser)

[Enetpulse](https://www.enetpulse.com/) database object builder for PHP

## Usage

```bash
composer require scandesignsdk/enetpulse-parser
```

Requires: PHP 7.2

### Configuration

```php
<?php

use SDM\Enetpulse\Configuration;

require __DIR__.'/../vendor/autoload.php';

// Setup our database connection
$config = new Configuration('mysql://root:WWXeOM8NgfNMT4h9j6B30Jyx6@mysql4.scandesigns.dk/oddsforum_dk_db');

// Which odds providers countries do we want to see
$config->setOddsProviderCountryNames(['Denmark', 'International']);

// We can also set specific odds providers by ID, get the ID from the table odds_provider
//$config->setOddsProviders([
//    1,
//    2,
//    3,
//]);

// Which tournaments do we want to see, find the ID's from the table tournament_template
$config->setTournamentTemplates([
    42,
    46,
    77,
    85,
    245,
    9408,
]);

?>
```

### Events

```php
<?php
$events = (new \SDM\Enetpulse\Generator($config))
    ->getEventProvider()
    // Lets find the latest 10 finished events
    ->getFinishedEvents(10)
    // We could also get upcoming, live, yesterdays, today, tomorrow,
//    ->getUpcomingEvents()
//    ->getLiveEvents()
//    ->getYesterdayEvents()
//    ->getTodayEvents()
//    ->getTomorrowEvents()
;

?>
```

##### Loop through events

```php
<?php foreach ($events as $event): ?>
    <?php echo $event->getId(); ?>
    <?php $hometeam = $event->getParticipants()[0]; ?>
    Hometeam: <?php echo $hometeam->getName(); ?>
    Hometeam logo: <img src="<?php echo $hometeam->getImage(); ?>
    Hometeam result: <?php echo $hometeam->getFirstResult()->getResult(); ?>

    Hometeam odds:
        <?php foreach ($hometeam->getOdds() as $odds): ?>
            - Odds type: <?php echo $odds->getScope(); ?>-<?php echo $odds->getSubtype(); ?>
            - Offers
            <?php foreach ($odds->getOffers() as $offer): ?>
                - Odds: <?php echo $offer->getOdds(); ?> (old odds: <?php echo $offer->getOldOdds(); ?>
                - Link: <?php echo $offer->getProvider()->getUrl().$offer->getCouponkey(); ?>
                - Provider: <?php echo $offer->getProvider()->getName(); ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

    <?php $awayteam = $event->getParticipants()[1]; ?>
    Awayteam: <?php echo $awayteam->getName(); ?>
    Awayteam logo: <img src="<?php echo $awayteam->getImage(); ?>
    Awayteam result: <?php echo $awayteam->getFirstResult()->getResult(); ?>

    Awayteam odds:
    <?php foreach ($awayteam->getOdds() as $odds): ?>
        - Odds type: <?php echo $odds->getScope(); ?>-<?php echo $odds->getSubtype(); ?>
        - Offers
        <?php foreach ($odds->getOffers() as $offer): ?>
            - Odds: <?php echo $offer->getOdds(); ?> (old odds: <?php echo $offer->getOldOdds(); ?>
            - Link: <?php echo $offer->getProvider()->getUrl().$offer->getCouponkey(); ?>
            - Provider: <?php echo $offer->getProvider()->getName(); ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>
```
