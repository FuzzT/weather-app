<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{

    public function index(Request $request)
    {
        $apiKey = env('WEATHER_API_KEY');
        $weatherResponse = [];

        if ($request->isMethod('post')) {
            $cityName = $request->input('city');

            $response = Http::withHeaders([
                'x-rapidapi-host' => 'yahoo-weather5.p.rapidapi.com',
                'x-rapidapi-key' => $apiKey,
            ])->get('https://yahoo-weather5.p.rapidapi.com/weather', [
                'location' => $cityName,
                'format' => 'json',
                'u' => 'f',
            ]);

            $weatherResponse = $response->json();
        }

        return view('weather', ['data' => $weatherResponse]);
    }
}
