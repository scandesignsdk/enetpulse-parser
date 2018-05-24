<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model;

use SDM\Enetpulse\Model\Event\Participant;
use SDM\Enetpulse\Model\Odds\HalftimeFullTime;
use SDM\Enetpulse\Model\Odds\Handicap;
use SDM\Enetpulse\Model\Odds\MatchWinnerHandicapOdds;
use SDM\Enetpulse\Model\Odds\MatchWinnerOdds;
use SDM\Enetpulse\Model\Odds\OverUnderGoalsOdds;
use SDM\Enetpulse\Model\Odds\OverUnderOdds;
use SDM\Enetpulse\Model\Tournament\TournamentStage;

class Event
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TournamentStage
     */
    private $stage;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var array|Participant[]
     */
    private $participants;

    /**
     * @var array|Odds[]
     */
    private $odds;

    /**
     * @param int $id
     * @param string $name
     * @param TournamentStage $stage
     * @param \DateTime $startDate
     * @param string $status
     * @param Participant[] $participants
     * @param array $odds
     */
    public function __construct(int $id, string $name, TournamentStage $stage, \DateTime $startDate, string $status, array $participants = [], array $odds = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->stage = $stage;
        $this->startDate = $startDate;
        $this->status = $status;
        $this->participants = $participants;
        $this->odds = $odds;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStage(): TournamentStage
    {
        return $this->stage;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array|Participant[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param Participant[] $participants
     *
     * @return Event
     */
    public function setParticipants(array $participants): self
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * @return array|Odds[]
     */
    public function getOdds(): array
    {
        return $this->odds;
    }

    /**
     * @param array|Odds[] $odds
     *
     * @return self
     */
    public function setOdds(array $odds): self
    {
        $this->odds = $odds;

        return $this;
    }

    /**
     * @return MatchWinnerOdds[]
     */
    public function getOrd1x2Odds(): array
    {
        $providers = [];

        $doOdds = function (Odds $odds, $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                if (!isset($providers[$offer->getProvider()->getId()])) {
                    $providers[$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($this->getOrd1x2HomeTeamOdds() as $odds) {
            $doOdds($odds, 'home');
        }

        foreach ($this->getOrd1x2DrawOdds() as $odds) {
            $doOdds($odds, 'draw');
        }

        foreach ($this->getOrd1x2AwayTeamOdds() as $odds) {
            $doOdds($odds, 'away');
        }

        $odds = [];
        foreach ($providers as $provider) {
            $odds[] = new MatchWinnerOdds(
                $provider['provider'],
                $provider['home'],
                $provider['draw'],
                $provider['away']
            );
        }

        return $odds;
    }

    /**
     * 1x2 - 3Way (ordinare time) - Home win (1)
     *
     * @return Odds[]
     */
    public function getOrd1x2HomeTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[0]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * 1x2 - 3Way (ordinare time) - Draw (x)
     *
     * @return Odds[]
     */
    public function getOrd1x2DrawOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'draw'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
            ;
        });
        return array_values($filtered);
    }

    /**
     * 1x2 - 3Way (ordinare time) - Away win (2)
     *
     * @return Odds[]
     */
    public function getOrd1x2AwayTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[1]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * @return MatchWinnerOdds[]
     */
    public function get1H1x2Odds(): array
    {
        $providers = [];

        $doOdds = function (Odds $odds, $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                if (!isset($providers[$offer->getProvider()->getId()])) {
                    $providers[$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($this->get1H1x2HomeTeamOdds() as $odds) {
            $doOdds($odds, 'home');
        }

        foreach ($this->get1H1x2DrawOdds() as $odds) {
            $doOdds($odds, 'draw');
        }

        foreach ($this->get1H1x2AwayTeamOdds() as $odds) {
            $doOdds($odds, 'away');
        }

        $odds = [];
        foreach ($providers as $provider) {
            $odds[] = new MatchWinnerOdds(
                $provider['provider'],
                $provider['home'],
                $provider['draw'],
                $provider['away']
            );
        }

        return $odds;
    }

    /**
     * 1x2 - 3Way (1. half) - Home win (1)
     *
     * @return Odds[]
     */
    public function get1H1x2HomeTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[0]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === '1h'
                ;
        });
        return array_values($filtered);
    }

    /**
     * 1x2 - 3Way (1. half) - Draw (x)
     *
     * @return Odds[]
     */
    public function get1H1x2DrawOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'draw'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === '1h'
                ;
        });
        return array_values($filtered);
    }

    /**
     * 1x2 - 3Way (1. half) - Away win (2)
     *
     * @return Odds[]
     */
    public function get1H1x2AwayTeamOdds(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[1]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2' && $odds->getScope() === '1h'
                ;
        });
        return array_values($filtered);
    }

    /**
     * Odd/Even goals - (ordinare time) - Even goals
     *
     * @return Odds[]
     */
    public function getOrdEvenGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'even'
                &&
                $odds->getType() === 'oe' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * Odd/Even goals - (ordinare time) - Odd goals
     *
     * @return Odds[]
     */
    public function getOrdOddGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'odd'
                &&
                $odds->getType() === 'oe' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * @param float $notGoals
     *
     * @return OverUnderGoalsOdds[]
     */
    public function getOrdOverUnderAll(float $notGoals = null): array
    {
        $providers = [];

        $over = $this->getOrdOverGoals();
        $under = $this->getOrdUnderGoals();
        if ($notGoals) {
            /** @var Odds[] $over */
            $over = array_filter($over, function (Odds $odds) use ($notGoals) {
                return $odds->getDparam1() !== $notGoals;
            });

            /** @var Odds[] $under */
            $under = array_filter($under, function (Odds $odds) use ($notGoals) {
                return $odds->getDparam1() !== $notGoals;
            });
        }

        $calculateProviders = function (Odds $odds, string $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                $goalKey = 'goals' . $odds->getDparam1();

                if (!isset($providers[$goalKey]['goals'])) {
                    $providers[$goalKey]['goals'] = $odds->getDparam1();
                }

                if (!isset($providers[$goalKey]['providers'][$offer->getProvider()->getId()])) {
                    $providers[$goalKey]['providers'][$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$goalKey]['providers'][$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($over as $overGoals) {
            $calculateProviders($overGoals, 'over');
        }

        foreach ($under as $underGoals) {
            $calculateProviders($underGoals, 'under');
        }

        $overunder = [];
        foreach ($providers as $provider) {
            $odds = [];
            foreach ($provider['providers'] as $pvd) {
                $odds[] = new OverUnderOdds($pvd['provider'], $pvd['over'] ?? null, $pvd['under'] ?? null);
            }
            $overunder[] = new OverUnderGoalsOdds($provider['goals'], $odds);
        }

        usort($overunder, function (OverUnderGoalsOdds $a, OverUnderGoalsOdds $b) {
            return $a->getGoals() <=> $b->getGoals();
        });

        return $overunder;
    }

    /**
     * @param float $numberGoals
     *
     * @return OverUnderGoalsOdds|null
     */
    public function getOrdOverUnderGoals(float $numberGoals): ?OverUnderGoalsOdds
    {
        /** @var Odds[] $over */
        $over = array_filter($this->getOrdOverGoals(), function (Odds $odds) use ($numberGoals) {
            return $odds->getDparam1() === $numberGoals;
        });

        /** @var Odds[] $under */
        $under = array_filter($this->getOrdUnderGoals(), function (Odds $odds) use ($numberGoals) {
            return $odds->getDparam1() === $numberGoals;
        });

        $providers = [];

        $calculateProviders = function (Odds $odds, string $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                $goalKey = 'goals' . $odds->getDparam1();

                if (!isset($providers[$goalKey]['goals'])) {
                    $providers[$goalKey]['goals'] = $odds->getDparam1();
                }

                if (!isset($providers[$goalKey]['providers'][$offer->getProvider()->getId()])) {
                    $providers[$goalKey]['providers'][$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$goalKey]['providers'][$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($over as $overGoals) {
            $calculateProviders($overGoals, 'over');
        }

        foreach ($under as $underGoals) {
            $calculateProviders($underGoals, 'under');
        }

        $overunder = null;
        foreach ($providers as $provider) {
            $odds = [];
            foreach ($provider['providers'] as $pvd) {
                $odds[] = new OverUnderOdds($pvd['provider'], $pvd['over'], $pvd['under']);
            }
            $overunder = new OverUnderGoalsOdds($provider['goals'], $odds);
        }

        return $overunder;
    }

    /**
     * Over/Under - (ordinare time) - Over
     * (the number is the dparam1 in the odds object)
     *
     * @return Odds[]
     */
    public function getOrdOverGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            $fraction = $odds->getDparam1() - floor($odds->getDparam1());
            return
                $odds->getSubtype() === 'over'
                &&
                $odds->getType() === 'ou' && $odds->getScope() === 'ord'
                &&
                ($fraction === 0 || $fraction === 0.5)
                ;
        });

        return array_values($filtered);
    }

    /**
     * Over/Under - (ordinare time) - Under
     * (the number is the dparam1 in the odds object)
     *
     * @return Odds[]
     */
    public function getOrdUnderGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            $fraction = $odds->getDparam1() - floor($odds->getDparam1());
            return
                $odds->getSubtype() === 'under'
                &&
                $odds->getType() === 'ou' && $odds->getScope() === 'ord'
                &&
                ($fraction === 0 || $fraction === 0.5)
                ;
        });
        return array_values($filtered);
    }

    /**
     * @param float $notGoals
     *
     * @return OverUnderGoalsOdds[]
     */
    public function get1HOverUnderAll(float $notGoals = null): array
    {
        $providers = [];

        $over = $this->get1HOverGoals();
        $under = $this->get1HUnderGoals();
        if ($notGoals) {
            /** @var Odds[] $over */
            $over = array_filter($over, function (Odds $odds) use ($notGoals) {
                return $odds->getDparam1() !== $notGoals;
            });

            /** @var Odds[] $under */
            $under = array_filter($under, function (Odds $odds) use ($notGoals) {
                return $odds->getDparam1() !== $notGoals;
            });
        }

        $calculateProviders = function (Odds $odds, string $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                $goalKey = 'goals' . $odds->getDparam1();

                if (!isset($providers[$goalKey]['goals'])) {
                    $providers[$goalKey]['goals'] = $odds->getDparam1();
                }

                if (!isset($providers[$goalKey]['providers'][$offer->getProvider()->getId()])) {
                    $providers[$goalKey]['providers'][$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$goalKey]['providers'][$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($over as $overGoals) {
            $calculateProviders($overGoals, 'over');
        }

        foreach ($under as $underGoals) {
            $calculateProviders($underGoals, 'under');
        }

        $overunder = [];
        foreach ($providers as $provider) {
            $odds = [];
            foreach ($provider['providers'] as $pvd) {
                $odds[] = new OverUnderOdds($pvd['provider'], $pvd['over'], $pvd['under']);
            }
            $overunder[] = new OverUnderGoalsOdds($provider['goals'], $odds);
        }

        return $overunder;
    }

    /**
     * @param float $numberGoals
     *
     * @return OverUnderGoalsOdds|null
     */
    public function get1HOverUnderGoals(float $numberGoals): ?OverUnderGoalsOdds
    {
        /** @var Odds[] $over */
        $over = array_filter($this->get1HOverGoals(), function (Odds $odds) use ($numberGoals) {
            return $odds->getDparam1() === $numberGoals;
        });

        /** @var Odds[] $under */
        $under = array_filter($this->get1HUnderGoals(), function (Odds $odds) use ($numberGoals) {
            return $odds->getDparam1() === $numberGoals;
        });

        $providers = [];

        $calculateProviders = function (Odds $odds, string $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                $goalKey = 'goals' . $odds->getDparam1();

                if (!isset($providers[$goalKey]['goals'])) {
                    $providers[$goalKey]['goals'] = $odds->getDparam1();
                }

                if (!isset($providers[$goalKey]['providers'][$offer->getProvider()->getId()])) {
                    $providers[$goalKey]['providers'][$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$goalKey]['providers'][$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($over as $overGoals) {
            $calculateProviders($overGoals, 'over');
        }

        foreach ($under as $underGoals) {
            $calculateProviders($underGoals, 'under');
        }

        $overunder = null;
        foreach ($providers as $provider) {
            $odds = [];
            foreach ($provider['providers'] as $pvd) {
                $odds[] = new OverUnderOdds($pvd['provider'], $pvd['over'], $pvd['under']);
            }
            $overunder = new OverUnderGoalsOdds($provider['goals'], $odds);
        }

        return $overunder;
    }

    /**
     * Over/Under - (1. half) - Over
     * (the number is the dparam1 in the odds object)
     *
     * @return Odds[]
     */
    public function get1HOverGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            $fraction = $odds->getDparam1() - floor($odds->getDparam1());
            return
                $odds->getSubtype() === 'over'
                &&
                $odds->getType() === 'ou' && $odds->getScope() === '1h'
                &&
                ($fraction === 0 || $fraction === 0.5)
                ;
        });
        return array_values($filtered);
    }

    /**
     * Over/Under - (1. half) - Under
     * (the number is the dparam1 in the odds object)
     *
     * @return Odds[]
     */
    public function get1HUnderGoals(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            $fraction = $odds->getDparam1() - floor($odds->getDparam1());
            return
                $odds->getSubtype() === 'under'
                &&
                $odds->getType() === 'ou' && $odds->getScope() === '1h'
                &&
                ($fraction === 0 || $fraction === 0.5)
                ;
        });
        return array_values($filtered);
    }

    /**
     * Asian Handicap (ordinar time)
     *
     * @return Handicap[]
     */
    public function getOrdAH(): array
    {
        $isFractionAllowed = function (float $number) {
            $whole = floor($number);
            $fraction = $number - $whole;
            return $fraction === 0 || $fraction === .5;
        };

        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === 'ah' && $odds->getScope() === 'ord'
                ;
        });

        $handicaps = [];
        foreach ($filtered as $odds) {
            if ($isFractionAllowed($odds->getDparam1())) {
                foreach ($this->getParticipants() as $participant) {
                    if ($participant->getId() === $odds->getIparam1()) {
                        $handicaps[] = new Handicap($this, $participant, $odds->getDparam1(), $odds->getOffers());
                        continue;
                    }
                }
            }
        }

        usort($handicaps, function (Handicap $a, Handicap $b) {
            return $a->getHandicap() <=> $b->getHandicap();
        });

        return $handicaps;
    }

    /**
     * @return MatchWinnerHandicapOdds[]
     */
    public function getOrd1x2OddsHandicap(): array
    {
        $providers = [];

        $doOdds = function (Handicap $handicap, $key) use (&$providers) {
            if (!isset($providers[$handicap->getHandicap()])) {
                $providers[$handicap->getHandicap()] = [
                    'handicap' => $handicap,
                    'odds' => []
                ];
            }

            foreach ($handicap->getOffers() as $offer) {
                if (!isset($providers[$handicap->getHandicap()]['odds'][$offer->getProvider()->getId()])) {
                    $providers[$handicap->getHandicap()]['odds'][$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$handicap->getHandicap()]['odds'][$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($this->getOrd1x2HomeTeamOddsHandicap() as $odds) {
            $doOdds($odds, 'home');
        }

        foreach ($this->getOrd1x2DrawHandicap() as $odds) {
            $doOdds($odds, 'draw');
        }

        foreach ($this->getOrd1x2AwayTeamOddsHandicap() as $odds) {
            $doOdds($odds, 'away');
        }

        ksort($providers);

        $odds = [];
        foreach ($providers as $handicap) {
            $handicapOdds = [];
            foreach ($handicap['odds'] as $provider) {
                $handicapOdds[] = new MatchWinnerOdds(
                    $provider['provider'],
                    $provider['home'] ?? null,
                    $provider['draw'] ?? null,
                    $provider['away'] ?? null
                );
            }
            $odds[] = new MatchWinnerHandicapOdds($handicap['handicap'], $handicapOdds);
        }

        return $odds;
    }

    /**
     * @return Handicap[]
     */
    public function getOrd1x2HomeTeamOddsHandicap(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[0]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2_hc' && $odds->getScope() === 'ord'
                ;
        });

        $handicaps = [];
        foreach ($filtered as $filter) {
            $handicaps[] = new Handicap($this, $this->getParticipants()[0], $filter->getDparam1(), $filter->getOffers());
        }
        return $handicaps;
    }

    /**
     * @return Handicap[]
     */
    public function getOrd1x2DrawHandicap(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'draw'
                &&
                $odds->getType() === '1x2_hc' && $odds->getScope() === 'ord'
                ;
        });

        $handicaps = [];
        foreach ($filtered as $filter) {
            $team = $this->getParticipants()[0]->getId() === $filter->getIparam1() ? $this->getParticipants()[0] : $this->getParticipants()[1];
            $handicaps[] = new Handicap($this, $team, $filter->getDparam1(), $filter->getOffers());
        }

        usort($handicaps, function (Handicap $a, Handicap $b) {
            return $a->getHandicap() <=> $b->getHandicap();
        });

        return $handicaps;
    }

    /**
     * @return Handicap[]
     */
    public function getOrd1x2AwayTeamOddsHandicap(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[1]->getId()
                &&
                $odds->getSubtype() === 'win'
                &&
                $odds->getType() === '1x2_hc' && $odds->getScope() === 'ord'
                ;
        });

        $handicaps = [];
        foreach ($filtered as $filter) {
            $handicaps[] = new Handicap($this, $this->getParticipants()[0], $filter->getDparam1(), $filter->getOffers());
        }
        return $handicaps;
    }

    /**
     * @return MatchWinnerOdds[]
     */
    public function getOrdDC(): array
    {
        $providers = [];
        $setOffer = function (Odds $odds, $key) use (&$providers) {
            foreach ($odds->getOffers() as $offer) {
                if (!isset($providers[$offer->getProvider()->getId()])) {
                    $providers[$offer->getProvider()->getId()] = [
                        'provider' => $offer->getProvider(),
                    ];
                }

                $providers[$offer->getProvider()->getId()][$key] = $offer;
            }
        };

        foreach ($this->getOrdHomeDC() as $odds) {
            $setOffer($odds, 'home');
        }

        foreach ($this->getOrdAwayDC() as $odds) {
            $setOffer($odds, 'away');
        }

        $odds = [];
        foreach ($providers as $provider) {
            $odds[] = new MatchWinnerOdds($provider['provider'], $provider['home'], null, $provider['away']);
        }
        return $odds;
    }

    /**
     * @return Odds[]
     */
    public function getOrdHomeDC(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[0]->getId()
                &&
                $odds->getSubtype() === 'win_draw'
                &&
                $odds->getType() === 'dc' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * @return Odds[]
     */
    public function getOrdAwayDC(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getIparam1() === $this->getParticipants()[1]->getId()
                &&
                $odds->getSubtype() === 'win_draw'
                &&
                $odds->getType() === 'dc' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * @return HalftimeFullTime[]
     */
    public function getOrdHalftimeFulltime(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getType() === 'ht_ft' && $odds->getScope() === 'ord'
            ;
        });

        $getTeam = function (int $id) {
            foreach ($this->getParticipants() as $participant) {
                if ($participant->getId() === $id) {
                    return $participant;
                }
            }
            return null;
        };

        $hftf = [];
        foreach ($filtered as $filter) {
            $hftf[] = new HalftimeFullTime($getTeam($filter->getIparam1()), $getTeam($filter->getIparam2()), $filter->getOffers());
        }
        return $hftf;
    }

    /**
     * @return Odds[]
     */
    public function getOrdBothTeamScoresYes(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'yes'
                &&
                $odds->getType() === 'bts' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }

    /**
     * @return Odds[]
     */
    public function getOrdBothTeamScoresNo(): array
    {
        $filtered = array_filter($this->odds, function (Odds $odds) {
            return
                $odds->getSubtype() === 'no'
                &&
                $odds->getType() === 'bts' && $odds->getScope() === 'ord'
                ;
        });
        return array_values($filtered);
    }
}
