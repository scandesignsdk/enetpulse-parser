<?php

namespace SDM\Enetpulse\Model\Odds;

class OverUnderOdds
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var Offer|null
     */
    private $over;

    /**
     * @var Offer|null
     */
    private $under;

    public function __construct(Provider $provider, Offer $over = null, Offer $under = null)
    {
        $this->provider = $provider;
        $this->over = $over;
        $this->under = $under;
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @return Offer|null
     */
    public function getOver(): ?Offer
    {
        return $this->over;
    }

    /**
     * @return Offer|null
     */
    public function getUnder(): ?Offer
    {
        return $this->under;
    }
}
