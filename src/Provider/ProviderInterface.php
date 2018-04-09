<?php

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Configuration;

interface ProviderInterface
{
    public function __construct(Configuration $configuration);
}
