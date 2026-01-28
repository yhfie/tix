<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'total' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'order_date',
        'total',
        'payment_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'order_details')
            ->withPivot('quantity', 'subtotal');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
