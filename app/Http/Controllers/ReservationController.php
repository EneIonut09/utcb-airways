<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\User;

class ReservationController extends Controller
{
    /**
     * Afișează rezervările utilizatorului autentificat
     */
    public function myReservations()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să te autentifici pentru a vedea rezervările.');
        }
        
        $reservations = Reservation::where('user_id', $user->id)
            ->with('flight') // Eager loading pentru a evita N+1 queries
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reservations.my-reservations', compact('reservations'));
    }

    /**
     * Afișează detaliile unei rezervări
     */
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
}