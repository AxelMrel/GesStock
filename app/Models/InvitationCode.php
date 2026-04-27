<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvitationCode extends Model
{
    protected $fillable = [
        'code',
        'is_used',
        'used_by',
        'used_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'used_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    // ── Génère un code unique aléatoire ────────────────────

    public static function generer(): string
    {
        do {
            $code = strtoupper(Str::random(4) . '-' . Str::random(4));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // ── Vérifie si le code est utilisable ──────────────────

    public function estValide(): bool
    {
        if ($this->is_used) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }

    // ── Marque le code comme utilisé ───────────────────────

    public function marquerUtilise(int $userId): void
    {
        $this->update([
            'is_used' => true,
            'used_by' => $userId,
            'used_at' => now(),
        ]);
    }

    // ── Relations ──────────────────────────────────────────

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}