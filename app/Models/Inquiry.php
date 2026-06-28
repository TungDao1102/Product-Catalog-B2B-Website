<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'name', 'email', 'phone', 'company', 'quantity', 'message', 'is_read'])]
class Inquiry extends Model
{
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
