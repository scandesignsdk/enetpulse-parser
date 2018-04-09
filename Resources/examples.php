<?php

require __DIR__.'/../vendor/autoload.php';

use SDM\Enetpulse\Configuration;
use SDM\Enetpulse\Generator;

$auth = new Configuration('mysql://root:root@localhost/enetpulse');

// Sports
$sports = (new Generator($auth))
    ->getSportProvider()
    ->getSports()
;

$sport = (new Generator($auth))
    ->getSportProvider()
    ->getSportByName('Soccer')
;

// Tournament
$tournaments = (new Generator($auth))
    ->getTournamentProvider()
    ->getTournaments()
;

$sport = (new Generator($auth))->getSportProvider()->getSportByName('Soccer');
$tournamentsByCountry = (new Generator($auth))
    ->getTournamentProvider()
    ->getTournamentsByCountry('Denmark', $sport)
;

$tournamentsBySeasonId = (new Generator($auth))
    ->getTournamentProvider()
    ->getTournamentByTournamentId(11488)
;
