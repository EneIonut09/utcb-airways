<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Flight;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_flights' => Flight::count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Nu puteți șterge un administrator.');
        }

        $user->delete();
        return back()->with('success', 'Utilizatorul a fost șters cu succes.');
    }

    public function flights()
    {
        $flights = Flight::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.manage-flights', compact('flights'));
    }

    public function deleteFlight($id)
    {
        $flight = Flight::findOrFail($id);
        $flight->delete();
        
        return back()->with('success', 'Zborul a fost șters cu succes.');
    }
}