<?php

namespace Naif\AIValidation\Rules;

use Exception;

class AIValidationRule
{
    public function validate($type, $value)
    {
        try {
            $chatgpt_api_key = config('ai-validation.chatgpt_api_key');
            if (!isset($chatgpt_api_key)) {
                throw new Exception('ChatGPT API key is required.');
            }
            $apiUrl = config('ai-validation.chatgpt_api_endpoint');

            $messages = $this->getMessageForType($type, $value);

            $postData = [
                'model' => 'gpt-3.5-turbo-1106',
                'messages' => $messages
            ];

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . config('ai-validation.chatgpt_api_key'),
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

            if (isset($responseData['choices'][0]['message']['content']) && is_string($responseData['choices'][0]['message']['content'])) {
                $result = json_decode($responseData['choices'][0]['message']['content'], true);
                if (isset($result['result'])) {
                    return $result['result'];
                } else {
                    throw new Exception('Invalid response format');
                }
            } else {
                throw new Exception('Missing or invalid content in response');
            }
        } catch (\Exception $e) {
            throw new Exception("Error processing the request: " . $e->getMessage());
        }
    }

    private function getMessageForType($type, $value)
    {
        $validation_tyeps = config('ai-validation.validation_types');
        if (!in_array($type, $validation_tyeps)) {
            throw new Exception('Validation type not supported.');
        } else {
            if ($type == 'spam') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting spam. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is spam (unsolicited, irrelevant, or inappropriate messages, especially of a commercial nature):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'nonsense') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting nonsensical text. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is nonsensical (lacking in meaningful or logical context):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'botcheck') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting bot-generated text. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is likely generated by a bot (automated, lacking human nuances):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'emoji_overuse') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting excessive use of emojis. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains an overuse of emojis (excessive or inappropriate amount of emojis):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'promotional') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting marketing or promotional content. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is marketing or promotional content:\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'ads') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting advertisements. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is an advertisement (promotional or selling a product/service):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'swearing') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting swearing or inappropriate language. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains swearing or inappropriate language:\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'hate_speech') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting hate speech. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains hate speech:\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'political_bias') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting political bias. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains political bias (favoring one political view over others):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'adult_content') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting adult content. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains adult content (explicit or sexual material):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'secure') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting SQL injection or XSS and any other web application vulnerabilities. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains SQL injection or XSS vulnerabilities:\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'phishing') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting phishing attempts. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it is a phishing attempt (trying to steal sensitive information like passwords or credit card numbers):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            } else if ($type == 'privacy') {
                $messages = [
                    ['role' => 'system', 'content' => 'You are an AI-based tool for detecting personal information. Respond only with a JSON object containing a \'result\' key with a boolean value.'],
                    ['role' => 'user', 'content' => "Please analyze the following text and determine if it contains personal information (like addresses, phone numbers, emails, social security numbers, etc.):\n\n{$value}\n\nRespond with: {\"result\": true} for detection, or {\"result\": false} otherwise."]
                ];
            }
            return $messages;
        }
    }
}
