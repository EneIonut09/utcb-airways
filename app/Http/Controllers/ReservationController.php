<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Flight;
use App\Models\User;
use App\Models\Passenger;

class ReservationController extends Controller
{
    public function myReservations()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să te autentifici pentru a vedea rezervările.');
        }
        
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['flight', 'passengers'])
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
            ->with(['flight', 'passengers'])
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
            'booking_class' => 'required|in:economy,business,first',
            'passengers' => 'required|array|min:1|max:10',
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.email' => 'required|email|max:255',
            'passengers.*.phone' => 'required|string|max:20',
            'passengers.*.date_of_birth' => 'nullable|date|before:today',
            'passengers.*.gender' => 'nullable|in:male,female,other',
            'passengers.*.passport_number' => 'nullable|string|max:50',
            'passengers.*.nationality' => 'nullable|string|max:100',
            'passengers.*.special_requests' => 'nullable|string|max:500'
        ]);

        $flight = Flight::findOrFail($request->flight_id);
        $passengerCount = count($request->passengers);

        if ($flight->available_seats < $passengerCount) {
            return back()->with('error', 'Nu sunt suficiente locuri disponibile pentru acest zbor.');
        }

        $basePrice = $flight->price;
        $classMultiplier = match($request->booking_class) {
            'business' => 2.0,
            'first' => 3.5,
            default => 1.0
        };
        $totalPrice = $basePrice * $classMultiplier * $passengerCount;

        DB::beginTransaction();
        
        try {
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'flight_id' => $request->flight_id,
                'booking_date' => now(),
                'status' => 'confirmed',
                'booking_class' => $request->booking_class,
                'total_price' => $totalPrice
            ]);

            foreach ($request->passengers as $passengerData) {
                Passenger::create([
                    'reservation_id' => $reservation->id,
                    'first_name' => $passengerData['first_name'],
                    'last_name' => $passengerData['last_name'],
                    'email' => $passengerData['email'],
                    'phone' => $passengerData['phone'],
                    'date_of_birth' => $passengerData['date_of_birth'] ?? null,
                    'gender' => $passengerData['gender'] ?? null,
                    'passport_number' => $passengerData['passport_number'] ?? null,
                    'nationality' => $passengerData['nationality'] ?? null,
                    'special_requests' => $passengerData['special_requests'] ?? null
                ]);
            }

            $flight->decrement('available_seats', $passengerCount);

            DB::commit();

            return redirect()->route('my-reservations')->with('success', 
                'Rezervarea a fost creată cu succes! Codul rezervării: ' . $reservation->booking_reference);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'A apărut o eroare la procesarea rezervării. Vă rugăm să încercați din nou.');
        }
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