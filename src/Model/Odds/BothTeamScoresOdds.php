<?php

namespace SDM\Enetpulse\Model\Odds;

class BothTeamScoresOdds
{
    /**
     * @var Provider
     */
    protected $provider;

    /**
     * @var Offer|null
     */
    protected $yesOffer;

    /**
     * @var Offer|null
     */
    protected $noOffer;

    /**
     * @param Provider $provider
     * @param Offer|null $yesOffer
     * @param Offer|null $noOffer
     */
    public function __construct(Provider $provider, $yesOffer, $noOffer)
    {
        $this->provider = $provider;
        $this->yesOffer = $yesOffer;
        $this->noOffer = $noOffer;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getYesOffer(): ?Offer
    {
        return $this->yesOffer;
    }

    public function getNoOffer(): ?Offer
    {
        return $this->noOffer;
    }
}
