<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'lu',
        'article_id',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    // ── Relations ──
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // ── Scopes ──
    public function scopeNonLues($query)
    {
        return $query->where('lu', false);
    }
}