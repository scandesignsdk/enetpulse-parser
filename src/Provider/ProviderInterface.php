<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Configuration;

interface ProviderInterface
{
    public function __construct(Configuration $configuration);
}
