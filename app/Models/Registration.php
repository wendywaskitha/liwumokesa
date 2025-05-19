<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_code',
        'event_id',
        'user_id',
        'number_of_tickets',
        'status',
        'registration_date',
        'payment_amount',
        'payment_method',
        'payment_date',
        'notes',
        'attendee_details',
        'is_paid'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'payment_date' => 'datetime',
        'is_paid' => 'boolean',
        'attendee_details' => 'json',
        'number_of_tickets' => 'integer',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        // Generate registration code automatically
        static::creating(function ($registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = 'REG-' . strtoupper(Str::random(8));
            }
            
            // Set registration date to now if not provided
            if (empty($registration->registration_date)) {
                $registration->registration_date = now();
            }
        });
    }

    /**
     * Event yang dihadiri
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * User yang mendaftar
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment status text attribute
     */
    public function getStatusTextAttribute()
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Terkonfirmasi',
            'cancelled' => 'Dibatalkan',
            'attended' => 'Hadir',
        ][$this->status] ?? $this->status;
    }

    /**
     * Get the payment method text attribute
     */
    public function getPaymentMethodTextAttribute()
    {
        return [
            'bank_transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'on_site' => 'Bayar di Tempat',
            'free' => 'Gratis',
        ][$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get total amount
     */
    public function getTotalAmountAttribute()
    {
        return $this->payment_amount;
    }

    /**
     * Scope untuk registrations dengan status tertentu
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk registrations yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope untuk registrations yang belum dibayar
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false)->where('status', 'pending');
    }

    /**
     * Generate QR code content
     */
    public function getQrCodeContentAttribute()
    {
        return json_encode([
            'id' => $this->id,
            'code' => $this->registration_code,
            'event' => $this->event->name,
            'user' => $this->user->name,
            'tickets' => $this->number_of_tickets,
            'status' => $this->status
        ]);
    }
}
