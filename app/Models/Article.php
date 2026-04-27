<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'categorie_id',
        'fournisseur_id',
        'quantite_stock',
        'stock_minimum',
        'prix_unitaire',
    ];

    protected $casts = [
        'prix_unitaire'  => 'decimal:2',
        'quantite_stock' => 'integer',
        'stock_minimum'  => 'integer',
    ];

    // ── Relations ──
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    // ── Helpers ──
    public function estEnAlerte(): bool
    {
        return $this->quantite_stock <= $this->stock_minimum;
    }

    public function valeurStock(): float
    {
        return $this->quantite_stock * $this->prix_unitaire;
    }
}