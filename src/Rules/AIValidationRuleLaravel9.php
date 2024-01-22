<?php

namespace Naif\AIValidation\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class AIValidationRuleLaravel9 extends AbstractAIValidationRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        $validate = new AIValidationRule();

        $result = $validate->validate($this->type, $value);

        if ($result) {
            $fail('The :attribute contains ' . $this->type . '.');
        }
    }
}
