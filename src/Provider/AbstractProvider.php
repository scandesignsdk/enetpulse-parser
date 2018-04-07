<?php

namespace SDM\Enetpulse\Provider;

use SDM\Enetpulse\Authentication;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var Authentication
     */
    protected $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }
}
