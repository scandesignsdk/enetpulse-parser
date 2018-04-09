<?php

namespace SDM\Enetpulse\Utils;

class Data extends \stdClass
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
