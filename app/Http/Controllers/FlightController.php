<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\Flight;

class FlightController extends Controller
{
    public function welcome() {
        return view('welcome');
    }

    public function home() {
        return view('home');
    }

    public function insertModel() {


        $flights = [[]];

    foreach ($flights as $flightData) {
        Flight::create($flightData);
    }
    
    return '<h1 style="color: #4caf50; text-align: center; font-family: Roboto, sans-serif; margin-top: 50px;">
                Success! Au fost adăugate 10 zboruri în baza de date.
            </h1>
            <p style="text-align: center; font-family: Roboto, sans-serif;">
                <a href="/display-model" style="color: #2c5aa0; text-decoration: none; font-weight: 500;">
                    Vezi toate zborurile
                </a> | 
                <a href="/home" style="color: #4caf50; text-decoration: none; font-weight: 500;">
                    Înapoi la pagina principală
                </a>
            </p>';

    }

    public function displayModel() {
        $flights = Flight::all();
    
    return view('flights', compact('flights'));
    }

    public function formular() {
        return view('flight-form');
    }

    public function post(Request $request) {
        $validatedData = $request->validate([
        'flight_number' => 'required|string|max:10|unique:flights,flight_number',
        'departure_city' => 'required|string|max:100',
        'arrival_city' => 'required|string|max:100|different:departure_city',
        'departure_time' => 'required|date|after:now',
        'arrival_time' => 'required|date|after:departure_time',
        'price' => 'required|numeric|min:0|max:9999.99',
        'available_seats' => 'required|integer|min:1|max:500',
        'aircraft_type' => 'required|string|max:50'
    ]);
    
    Flight::create($validatedData);
    
    return redirect('/formular')->with('success', 'Zbor adăugat cu succes!');
    }

    public function comingSoon() {
    return view('coming-soon');
}

}