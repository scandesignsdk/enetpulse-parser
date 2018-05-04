<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model;

use SDM\Enetpulse\Model\Odds\Offer;

class Odds
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $subtype;

    /**
     * @var array|Offer[]
     */
    private $offers;
    /**
     * @var float
     */
    private $dparam1;
    /**
     * @var float
     */
    private $dparam2;
    /**
     * @var mixed
     */
    private $sparam;
    /**
     * @var mixed
     */
    private $iparam1;
    /**
     * @var mixed
     */
    private $iparam2;

    /**
     * @param int $id
     * @param string $type
     * @param string $scope
     * @param string $subtype
     * @param float $dparam1
     * @param float $dparam2
     * @param mixed $sparam
     * @param int $iparam1
     * @param int $iparam2
     * @param Offer[] $offers
     */
    public function __construct(int $id, string $type, string $scope, string $subtype, float $dparam1, float $dparam2, $sparam, int $iparam1, int $iparam2, array $offers = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->scope = $scope;
        $this->subtype = $subtype;
        $this->dparam1 = $dparam1;
        $this->dparam2 = $dparam2;
        $this->sparam = $sparam;
        $this->iparam1 = $iparam1;
        $this->iparam2 = $iparam2;
        $this->offers = $offers;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
    }

    public function getDparam1(): float
    {
        return $this->dparam1;
    }

    public function getDparam2(): float
    {
        return $this->dparam2;
    }

    public function getSparam()
    {
        return $this->sparam;
    }

    public function getIparam1(): int
    {
        return $this->iparam1;
    }

    public function getIparam2(): int
    {
        return $this->iparam2;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        $this->offers[] = $offer;

        return $this;
    }
}
