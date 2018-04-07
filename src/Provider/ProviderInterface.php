<?php

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Authentication;

interface ProviderInterface
{
    public function __construct(Authentication $authentication);
}
