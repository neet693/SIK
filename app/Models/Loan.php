<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function loaner(): BelongsTo
    {
        return $this->belongsTo(Loaner::class);
    }

    public function loan_type(): BelongsTo
    {
        return $this->belongsTo(LoanType::class);
    }
}
