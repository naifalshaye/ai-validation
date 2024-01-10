<?php

namespace Naif\SpamValidationRule;

use Illuminate\Contracts\Validation\Rule;
use OpenAI;

class SpamRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $apiKey = env('OPENAI_API_KEY');
        $open_ai = new OpenAI($apiKey);

        $result = $open_ai->chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a forum moderator who always responds using JSON.'],
                [
                    'role' => 'user',
                    'content' => <<<EOT
                        Please inspect the following text and determine if it is spam.
                        {$value}
                        Expected Response Example:
                        {"is_spam": true|false}
                        EOT
                ],
            ],
            'response_format' => ['type' => 'json_object']
        ])->choices[0]->message->content;

        $result = json_decode($result,true);
        return $result['is_spam'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains spam.';
    }
}