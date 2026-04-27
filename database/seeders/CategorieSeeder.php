<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Ordinateurs', 'description' => 'PC portables et bureaux'],
            ['nom' => 'Périphériques', 'description' => 'Claviers, souris, écrans'],
            ['nom' => 'Réseaux', 'description' => 'Routeurs, switchs, câbles'],
            ['nom' => 'Stockage', 'description' => 'Disques durs, SSD, clés USB'],
            ['nom' => 'Composants', 'description' => 'RAM, processeurs, cartes mères'],
            ['nom' => 'Impression', 'description' => 'Imprimantes et scanners'],
            ['nom' => 'Accessoires', 'description' => 'Casques, tapis, webcams'],
            ['nom' => 'Logiciels', 'description' => 'Systèmes et applications'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}