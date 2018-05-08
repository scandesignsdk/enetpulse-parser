<?php

namespace SDM\Enetpulse\Model\Standing;

class Participant
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
    private $type;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $image;

    /**
     * @param int $id
     * @param string $name
     * @param string $type
     * @param string $country
     * @param string $image
     */
    public function __construct(int $id, string $name, string $type, string $country, string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->country = $country;
        $this->image = $image;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
