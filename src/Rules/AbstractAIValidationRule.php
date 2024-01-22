<?php

namespace Naif\AIValidation\Rules;

class AbstractAIValidationRule
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
