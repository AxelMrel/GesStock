<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'quantite',
        'motif',
        'article_id',
        'user_id',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];

    // ── Relations ──
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──
    public function estEntree(): bool
    {
        return $this->type === 'entree';
    }

    public function estSortie(): bool
    {
        return $this->type === 'sortie';
    }
}