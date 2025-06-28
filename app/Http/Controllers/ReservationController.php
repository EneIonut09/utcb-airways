<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Flight;
use App\Models\User;

class ReservationController extends Controller
{
    
    public function myReservations()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să te autentifici pentru a vedea rezervările.');
        }
        
        $reservations = Reservation::where('user_id', $user->id)
            ->with('flight')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reservations.my-reservations', compact('reservations'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $reservation = Reservation::where('user_id', $user->id)
            ->where('id', $id)
            ->with('flight')
            ->firstOrFail();

        return view('reservations.show', compact('reservation'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să te autentifici pentru a face o rezervare.');
        }

        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'number_of_passengers' => 'required|integer|min:1|max:10',
            'special_requests' => 'nullable|string|max:500'
        ]);

        $flight = Flight::findOrFail($request->flight_id);

        if ($flight->available_seats < $request->number_of_passengers) {
            return back()->with('error', 'Nu sunt suficiente locuri disponibile pentru acest zbor.');
        }

        $totalPrice = $flight->price * $request->number_of_passengers;

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'flight_id' => $request->flight_id,
            'passenger_name' => $request->passenger_name,
            'passenger_email' => $request->passenger_email,
            'passenger_phone' => $request->passenger_phone,
            'booking_date' => now(),
            'status' => 'confirmed',
            'total_price' => $totalPrice,
            'number_of_passengers' => $request->number_of_passengers,
            'special_requests' => $request->special_requests
        ]);

        $flight->decrement('available_seats', $request->number_of_passengers);

        return redirect()->route('my-reservations')->with('success', 'Rezervarea a fost creată cu succes! Numărul rezervării: ' . $reservation->id);
    }

    public function create($flightId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să te autentifici pentru a face o rezervare.');
        }

        $flight = Flight::findOrFail($flightId);

        if ($flight->available_seats <= 0) {
            return redirect()->back()->with('error', 'Nu mai sunt locuri disponibile pentru acest zbor.');
        }

        return view('reservations.create', compact('flight'));
    }
}