<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Favorite;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FashionController extends Controller
{
    public function showUploadForm()
    {
        return view('fashion.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upper.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'lower.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $upperImages = [];
        $lowerImages = [];

        if ($request->hasfile('upper')) {
            foreach ($request->file('upper') as $file) {
                $path = $file->store('uploads', 'public');
                $upperImages[] = Image::create([
                    'user_id' => $user->id,
                    'image_path' => $path,
                    'category' => 'upper',
                ]);
            }
        }

        if ($request->hasfile('lower')) {
            foreach ($request->file('lower') as $file) {
                $path = $file->store('uploads', 'public');
                $lowerImages[] = Image::create([
                    'user_id' => $user->id,
                    'image_path' => $path,
                    'category' => 'lower',
                ]);
            }
        }

        $upperImageUrls = $this->getImageUrls($upperImages);
        $lowerImageUrls = $this->getImageUrls($lowerImages);
        $combination = $this->getFashionCombination($upperImageUrls, $lowerImageUrls);

        $this->saveFashionCombination($user->id, $combination);

        return view('fashion.results', compact('combination'));
    }

    public function showResults()
    {
        $favorites = Favorite::with(['upperImage', 'lowerImage'])->where('user_id', Auth::id())->get();
        return view('fashion.results', compact('favorites'));
    }

    private function getImageUrls($images)
    {
        return array_map(function($image) {
            return Storage::url($image->image_path);
        }, $images);
    }

    private function getFashionCombination($upperImageUrls, $lowerImageUrls)
{
    $client = new Client();
    $retryCount = 0;
    $maxRetries = 3;
    $waitTime = 10; // 秒

    $apiKey = env('OPENAI_API_KEY'); // 直接APIキーを設定

    do {
        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a fashion expert. Suggest the best combination of upper and lower clothing from the given images.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $this->generatePrompt($upperImageUrls, $lowerImageUrls)
                        ],
                    ],
                    'max_tokens' => 300,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'];

        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode == 429 && $retryCount < $maxRetries) {
                sleep($waitTime);
                $retryCount++;
            } else {
                error_log('Error: ' . $e->getMessage());
                throw $e;
            }
        }
    } while ($retryCount < $maxRetries);

    throw new \Exception('Maximum retries exceeded');
}


    private function generatePrompt($upperImageUrls, $lowerImageUrls)
    {
        $prompt = "Here are some upper clothing images:\n";
        foreach ($upperImageUrls as $url) {
            $prompt .= "$url\n";
        }
        $prompt .= "Here are some lower clothing images:\n";
        foreach ($lowerImageUrls as $url) {
            $prompt .= "$url\n";
        }
        $prompt .= "Based on the images, please suggest the best combination of upper and lower clothing.";
        return $prompt;
    }

    private function saveFashionCombination($userId, $combination)
    {
        $upperImage = Image::where('category', 'upper')->inRandomOrder()->first();
        $lowerImage = Image::where('category', 'lower')->inRandomOrder()->first();

        if ($upperImage && $lowerImage) {
            Favorite::create([
                'user_id' => $userId,
                'upper_image_id' => $upperImage->id,
                'lower_image_id' => $lowerImage->id,
                'saved_date' => now(),
            ]);
        }
    }
}



