<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = [
            [
                'nom'       => 'Informatique Bénin SARL',
                'telephone' => '+229 01 23 45 67',
                'email'     => 'contact@infobénin.bj',
                'adresse'   => 'Avenue Jean-Paul II, Cotonou, Bénin',
            ],
            [
                'nom'       => 'TechnoAfrique',
                'telephone' => '+229 01 98 76 54',
                'email'     => 'ventes@technoafrique.bj',
                'adresse'   => 'Rue des Palmiers, Porto-Novo, Bénin',
            ],
            [
                'nom'       => 'DigiStore Bénin',
                'telephone' => '+229 01 55 44 33',
                'email'     => 'info@digistore.bj',
                'adresse'   => 'Boulevard de la Marina, Cotonou, Bénin',
            ],
            [
                'nom'       => 'Global Tech Import',
                'telephone' => '+229 01 11 22 33',
                'email'     => 'import@globaltech.bj',
                'adresse'   => 'Zone Industrielle, Cotonou, Bénin',
            ],
            [
                'nom'       => 'CyberShop Parakou',
                'telephone' => '+229 01 77 88 99',
                'email'     => 'cybershop@parakou.bj',
                'adresse'   => 'Rue du Commerce, Parakou, Bénin',
            ],
        ];

        foreach ($fournisseurs as $f) {
            Fournisseur::create($f);
        }
    }
}