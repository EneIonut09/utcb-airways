<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Flight;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $flights = Flight::all();

        if ($users->isEmpty()) {
            $this->command->error('Nu există utilizatori în baza de date!');
            return;
        }

        if ($flights->isEmpty()) {
            $this->command->error('Nu există zboruri în baza de date!');
            return;
        }

        $passengerNames = [
            'Ion Popescu',
            'Maria Ionescu', 
            'Alexandru Georgescu',
            'Elena Dumitrescu',
            'Mihai Constantinescu',
            'Ana Stoica',
            'Bogdan Radu',
            'Carmen Vlad',
            'Cristian Marin',
            'Diana Pavel',
            'Radu Stoian',
            'Andreea Popa'
        ];

        $specialRequests = [
            null,
            null,
            null,
            'Loc la fereastră',
            'Masă vegetariană',
            'Asistență pentru mobilitate redusă',
            'Loc la culoar',
            'Bagaj suplimentar'
        ];

        $totalReservations = 0;

        foreach ($users as $user) {
            for ($i = 1; $i <= 2; $i++) {
                $flight = $flights->random();
                $passengerName = $passengerNames[array_rand($passengerNames)];
                $numberOfPassengers = rand(1, 4);
                
                Reservation::create([
                    'user_id' => $user->id,
                    'flight_id' => $flight->id,
                    'passenger_name' => $passengerName,
                    'passenger_email' => strtolower(str_replace(' ', '.', $passengerName)) . '@email.com',
                    'passenger_phone' => '072' . rand(1000000, 9999999),
                    'booking_date' => now()->subDays(rand(1, 30)),
                    'status' => rand(1, 10) <= 8 ? 'confirmed' : 'pending',
                    'total_price' => $flight->price * $numberOfPassengers,
                    'number_of_passengers' => $numberOfPassengers,
                    'special_requests' => $specialRequests[array_rand($specialRequests)],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);

                $totalReservations++;
            }
        }

        $this->command->info("Au fost create {$totalReservations} rezervări pentru {$users->count()} utilizatori!");
    }
}