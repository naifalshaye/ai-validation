<?php

namespace Naif\AIValidation\Rules;

class AIValidation
{
    public static function make($type = 'spam')
    {
        $version = app()->version();
        if (version_compare($version, '10.0.0', '>=')) {
            return new AIValidationRuleLaravel10($type);
        } elseif (version_compare($version, '9.0.0', '>=')) {
            return new AIValidationRuleLaravel9($type);
        } else {
            return new AIValidationRuleLaravel8($type);
        }
    }
}
