<?php

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
     * @param int     $id
     * @param string  $scope
     * @param string  $subtype
     * @param Offer[] $offers
     */
    public function __construct(int $id, string $scope, string $subtype, array $offers = [])
    {
        $this->id = $id;
        $this->scope = $scope;
        $this->subtype = $subtype;
        $this->offers = $offers;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
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
