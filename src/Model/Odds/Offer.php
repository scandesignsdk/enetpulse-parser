<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model\Odds;

class Offer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var float
     */
    private $odds;

    /**
     * @var float
     */
    private $oldOdds;

    /**
     * @var int
     */
    private $volume;

    /**
     * @var null|string
     */
    private $currency;

    /**
     * @var null|string
     */
    private $couponkey;

    public function __construct(int $id, Provider $provider, float $odds, float $oldOdds, int $volume, string $currency, ?string $couponkey)
    {
        $this->id = $id;
        $this->provider = $provider;
        $this->odds = $odds;
        $this->oldOdds = $oldOdds;
        $this->volume = $volume;
        $this->currency = 'none' === $currency ? null : $currency;
        $this->couponkey = $couponkey;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getOdds(): float
    {
        return round($this->odds, 2);
    }

    public function getRawOdds(): float
    {
        return $this->odds;
    }

    public function getOldOdds(): float
    {
        return $this->oldOdds;
    }

    public function getVolume(): int
    {
        return $this->volume;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getCouponkey(): ?string
    {
        return $this->couponkey;
    }
}
