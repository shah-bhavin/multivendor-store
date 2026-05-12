<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class AIService
{
    /*
    |--------------------------------------------------------------------------
    | Generate Product Description
    |--------------------------------------------------------------------------
    */

    public function generateDescription(
        $productName
    )
    {
        $response = OpenAI::chat()->create([

            'model' => 'gpt-4.1-mini',

            'messages' => [

                [
                    'role' => 'system',

                    'content' =>

                        'You are an expert eCommerce copywriter.',
                ],

                [
                    'role' => 'user',

                    'content' =>

                        "Write a professional product description for {$productName}",
                ],
            ],
        ]);

        return $response

            ->choices[0]

            ->message

            ->content;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate SEO Meta
    |--------------------------------------------------------------------------
    */

    public function generateSeo(
        $productName
    )
    {
        $response = OpenAI::chat()->create([

            'model' => 'gpt-4.1-mini',

            'messages' => [

                [
                    'role' => 'system',

                    'content' =>

                        'Generate SEO title and meta description.',
                ],

                [
                    'role' => 'user',

                    'content' =>

                        "Generate SEO meta for {$productName}",
                ],
            ],
        ]);

        return $response

            ->choices[0]

            ->message

            ->content;
    }

    public function summarizeReviews(
        $reviews
    )
    {
        $response = OpenAI::chat()->create([

            'model' => 'gpt-4.1-mini',

            'messages' => [

                [
                    'role' => 'system',

                    'content' =>

                        'Summarize customer reviews professionally.',
                ],

                [
                    'role' => 'user',

                    'content' => $reviews,
                ],
            ],
        ]);

        return $response
            ->choices[0]
            ->message
            ->content;
    }
}