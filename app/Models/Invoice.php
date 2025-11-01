<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\ModelStates\InvoiceState;
use App\Modules\ModelStates\Created;
use Spatie\ModelStates\HasStates;

class Invoice extends Model
{
    use HasFactory, HasStates, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'state' => InvoiceState::class,
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
    }

    // protected function registerStates(): void
    // {
    //     $this->addState('state', InvoiceState::class);
    // }

    // protected static function booted(): void
    // {
    //     static::creating(function ($invoice) {
    //         if (is_null($invoice->state)) {
    //             $invoice->state = Created::class;
    //         }
    //     });
    // }

    //

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid()
    {
        return $this->payments->sum('amount');
    }
}
