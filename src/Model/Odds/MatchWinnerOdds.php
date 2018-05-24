<?php

namespace SDM\Enetpulse\Model\Odds;

class MatchWinnerOdds
{

    /**
     * @var Provider
     */
    protected $provider;

    /**
     * @var Offer|null
     */
    protected $homeOffer;

    /**
     * @var Offer|null
     */
    protected $drawOffer;

    /**
     * @var Offer|null
     */
    protected $awayOffer;

    /**
     * @param Provider $provider
     * @param Offer|null $homeOffer
     * @param Offer|null $drawOffer
     * @param Offer|null $awayOffer
     */
    public function __construct(Provider $provider, $homeOffer, $drawOffer, $awayOffer)
    {
        $this->provider = $provider;
        $this->homeOffer = $homeOffer;
        $this->drawOffer = $drawOffer;
        $this->awayOffer = $awayOffer;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getHomeOffer(): ?Offer
    {
        return $this->homeOffer;
    }

    public function getDrawOffer(): ?Offer
    {
        return $this->drawOffer;
    }

    public function getAwayOffer(): ?Offer
    {
        return $this->awayOffer;
    }

}
