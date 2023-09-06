<?php

namespace App\Classes\Helper;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class OpenAi
{
    public static function generateMessage($prompt) {
        try {
            $client = new Client();

            $response = $client->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("OPEN_AI_KEY"),
                ],
                'json' => [
                    'prompt' => $prompt,
                    'max_tokens' => 150,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $aiData = [];
            if (isset($data['choices'][0]['text'])) {
                $aiData['success'] = true;
                $aiData['ai_message'] = $data['choices'][0]['text'];
                Log::info('Message generate successfully');
            }
            return $aiData;
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
    }
}