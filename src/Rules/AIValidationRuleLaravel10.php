<?php

namespace Naif\AIValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AIValidationRuleLaravel10 extends AbstractAIValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validate = new AIValidationRule();

        $result = $validate->validate($this->type, $value);

        if ($result) {
            $fail('The :attribute contains ' . $this->type . '.');
        }
    }
}
