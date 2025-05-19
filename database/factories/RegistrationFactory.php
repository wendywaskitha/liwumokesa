<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        $event = Event::inRandomOrder()->first() ?? Event::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $tickets = $this->faker->numberBetween(1, 5);
        $isFree = $event->is_free;
        $amount = $isFree ? 0 : $event->ticket_price * $tickets;
        $status = $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'attended']);
        $isPaid = in_array($status, ['confirmed', 'attended']);

        return [
            'registration_code' => 'REG-' . strtoupper($this->faker->unique()->regexify('[A-Z0-9]{8}')),
            'event_id' => $event->id,
            'user_id' => $user->id,
            'number_of_tickets' => $tickets,
            'status' => $status,
            'registration_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'payment_amount' => $amount,
            'payment_method' => $isFree ? 'free' : $this->faker->randomElement(['bank_transfer', 'e_wallet', 'on_site']),
            'payment_date' => $isPaid ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'attendee_details' => $this->faker->optional(0.5)->randomElements([
                'special_needs' => $this->faker->boolean(10),
                'dietary_restrictions' => $this->faker->optional(0.3)->randomElement(['vegetarian', 'vegan', 'gluten-free', 'halal']),
                'age_group' => $this->faker->randomElement(['adult', 'child', 'senior']),
            ]),
            'is_paid' => $isPaid,
        ];
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'is_paid' => false,
            'payment_date' => null,
        ]);
    }

    public function confirmed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'is_paid' => true,
            'payment_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function attended(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'attended',
            'is_paid' => true,
            'payment_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function cancelled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'is_paid' => false,
        ]);
    }
}
