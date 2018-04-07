<?php

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Model\Sport;

class SportProvider extends AbstractProvider
{
    /**
     * @return Sport[]
     */
    public function getSports(): array
    {
    }

    public function getSportByName(string $name): Sport
    {
    }
}
