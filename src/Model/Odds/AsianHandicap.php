<?php

namespace SDM\Enetpulse\Model\Odds;

class AsianHandicap
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var Offer
     */
    private $offers;

    /**
     * @param Provider $provider
     * @param Offer $offers
     */
    public function __construct(Provider $provider, Offer $offers)
    {
        $this->provider = $provider;
        $this->offers = $offers;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getOffers(): Offer
    {
        return $this->offers;
    }
}
