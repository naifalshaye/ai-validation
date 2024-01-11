<?php

namespace Naif\SpamValidationRule;

use Exception;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SpamRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $apiUrl = 'https://api.openai.com/v1/chat/completions';

            $postData = [
                'model' => 'gpt-3.5-turbo-1106',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a forum moderator who always responds using JSON.'],
                    [
                        'role' => 'user',
                        'content' => "Please inspect the following text and determine if it is spam.\n" . $value . "\nExpected Response Example:\n{\"is_spam\": true|false}"
                    ],
                ]
            ];

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . env('CHATGPT_API_KEY'),
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            } elseif ($httpCode != 200) {
                throw new Exception("OpenAI API request failed with status code: " . $httpCode);
            }

            curl_close($ch);

            $responseData = json_decode($response, true);

            if (json_last_error() != JSON_ERROR_NONE) {
                throw new Exception("JSON decoding error: " . json_last_error_msg());
            }

            if (isset($responseData['choices'][0]['message']['content'])) {
                $result = $responseData['choices'][0]['message']['content'];
                $resultArray = json_decode($result, true);

                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new Exception("JSON decoding error: " . json_last_error_msg());
                }

                if ($resultArray['is_spam'] === false) {
                    $fail('The :attribute contains spam.');
                }
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}