<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Tests;

class Data extends \stdClass
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
