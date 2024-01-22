<?php

namespace Naif\AIValidation\Rules;

use Illuminate\Contracts\Validation\Rule;

class AIValidationRuleLaravel8 extends AbstractAIValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        $validate = new AIValidationRule();

        return $validate->validate($this->type, $value);
    }

    public function message()
    {
        return 'The :attribute contains ' . $this->type;
    }
}
