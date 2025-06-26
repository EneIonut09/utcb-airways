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
        Flight::truncate();

        $flights = [
        [
            'flight_number' => 'UT101',
            'departure_city' => 'București',
            'arrival_city' => 'Paris',
            'departure_time' => '2024-12-25 08:00:00',
            'arrival_time' => '2024-12-25 11:30:00',
            'price' => 299.99,
            'available_seats' => 150,
            'aircraft_type' => 'Boeing 737'
        ],
        [
            'flight_number' => 'UT102',
            'departure_city' => 'București',
            'arrival_city' => 'Londra',
            'departure_time' => '2024-12-25 14:00:00',
            'arrival_time' => '2024-12-25 16:45:00',
            'price' => 249.99,
            'available_seats' => 180,
            'aircraft_type' => 'Airbus A320'
        ],
        [
            'flight_number' => 'UT103',
            'departure_city' => 'București',
            'arrival_city' => 'Roma',
            'departure_time' => '2024-12-26 09:30:00',
            'arrival_time' => '2024-12-26 11:15:00',
            'price' => 199.99,
            'available_seats' => 160,
            'aircraft_type' => 'Boeing 737'
        ],
        [
            'flight_number' => 'UT104',
            'departure_city' => 'Paris',
            'arrival_city' => 'București',
            'departure_time' => '2024-12-26 15:00:00',
            'arrival_time' => '2024-12-26 18:30:00',
            'price' => 319.99,
            'available_seats' => 140,
            'aircraft_type' => 'Boeing 787'
        ],
        [
            'flight_number' => 'UT105',
            'departure_city' => 'Londra',
            'arrival_city' => 'București',
            'departure_time' => '2024-12-27 12:00:00',
            'arrival_time' => '2024-12-27 16:45:00',
            'price' => 279.99,
            'available_seats' => 170,
            'aircraft_type' => 'Airbus A321'
        ],
        [
            'flight_number' => 'UT106',
            'departure_city' => 'București',
            'arrival_city' => 'Madrid',
            'departure_time' => '2024-12-28 07:45:00',
            'arrival_time' => '2024-12-28 10:30:00',
            'price' => 349.99,
            'available_seats' => 190,
            'aircraft_type' => 'Boeing 737'
        ],
        [
            'flight_number' => 'UT107',
            'departure_city' => 'București',
            'arrival_city' => 'Amsterdam',
            'departure_time' => '2024-12-28 16:20:00',
            'arrival_time' => '2024-12-28 19:10:00',
            'price' => 289.99,
            'available_seats' => 155,
            'aircraft_type' => 'Airbus A320'
        ],
        [
            'flight_number' => 'UT108',
            'departure_city' => 'Roma',
            'arrival_city' => 'București',
            'departure_time' => '2024-12-29 13:15:00',
            'arrival_time' => '2024-12-29 17:00:00',
            'price' => 229.99,
            'available_seats' => 165,
            'aircraft_type' => 'Boeing 787'
        ],
        [
            'flight_number' => 'UT109',
            'departure_city' => 'Madrid',
            'arrival_city' => 'București',
            'departure_time' => '2024-12-30 11:30:00',
            'arrival_time' => '2024-12-30 16:15:00',
            'price' => 369.99,
            'available_seats' => 175,
            'aircraft_type' => 'Airbus A321'
        ],
        [
            'flight_number' => 'UT110',
            'departure_city' => 'Amsterdam',
            'arrival_city' => 'București',
            'departure_time' => '2024-12-31 08:00:00',
            'arrival_time' => '2024-12-31 12:50:00',
            'price' => 309.99,
            'available_seats' => 145,
            'aircraft_type' => 'Boeing 737'
        ]
    ];

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
}