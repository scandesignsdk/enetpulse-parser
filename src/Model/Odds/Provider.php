<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Model\Odds;

class Provider
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
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $country;

    /**
     * @var bool
     */
    private $bookmaker;

    public function __construct(int $id, string $name, string $url, string $country, bool $bookmaker)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->country = $country;
        $this->bookmaker = $bookmaker;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function isBookmaker(): bool
    {
        return $this->bookmaker;
    }
}
