<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada untuk mencegah duplikasi
        if (app()->environment() !== 'production') {
            Registration::truncate();
        }

        // Check if events and users exist
        $events = Event::all();
        $users = User::where('id', '>', 1)->get(); // Skip admin user

        if ($events->isEmpty()) {
            $this->command->info('Tidak ada event yang tersedia untuk registrasi.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada user yang tersedia untuk registrasi.');
            return;
        }

        // Create registrations
        $this->command->info('Membuat data registrasi...');
        
        $events->each(function ($event) use ($users) {
            // Random number of registrations per event (between 5-20)
            $registrationCount = rand(5, 20);
            
            // Get random users, making sure we don't exceed available users
            $attendees = $users->random(min($registrationCount, $users->count()));
            
            foreach ($attendees as $user) {
                $tickets = rand(1, 3); // 1-3 tickets per registration
                $isFree = $event->is_free;
                $amount = $isFree ? 0 : $event->ticket_price * $tickets;
                
                // Determine status with probabilities
                $statusOptions = [
                    'pending' => 20,
                    'confirmed' => 60,
                    'cancelled' => 10,
                    'attended' => 10,
                ];
                
                $status = $this->getRandomWeighted($statusOptions);
                $isPaid = in_array($status, ['confirmed', 'attended']);
                
                // Create registration
                $registration = Registration::create([
                    'registration_code' => 'REG-' . strtoupper(Str::random(8)),
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'number_of_tickets' => $tickets,
                    'status' => $status,
                    'registration_date' => Carbon::now()->subDays(rand(1, 30)),
                    'payment_amount' => $amount,
                    'payment_method' => $isFree ? 'free' : $this->getRandomWeighted([
                        'bank_transfer' => 60, 
                        'e_wallet' => 30, 
                        'on_site' => 10
                    ]),
                    'payment_date' => $isPaid ? Carbon::now()->subDays(rand(1, 29)) : null,
                    'notes' => rand(1, 10) > 7 ? 'Catatan: ' . Str::random(20) : null,
                    'is_paid' => $isPaid,
                ]);
            }
        });

        $this->command->info(Registration::count() . ' registrasi berhasil dibuat!');
    }

    /**
     * Get a random value based on weighted probabilities
     */
    private function getRandomWeighted(array $weightedValues)
    {
        $rand = rand(1, array_sum($weightedValues));
        
        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        
        return array_key_first($weightedValues);
    }
}
