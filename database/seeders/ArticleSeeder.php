<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $ordinateurs  = Categorie::where('nom', 'Ordinateurs')->first()->id;
        $laptop       = Categorie::where('nom', 'Laptop')->first()->id;
        $peripherique = Categorie::where('nom', 'Périphériques')->first()->id;
        $reseau       = Categorie::where('nom', 'Réseaux')->first()->id;
        $stockage     = Categorie::where('nom', 'Stockage')->first()->id;
        $composant    = Categorie::where('nom', 'Composants')->first()->id;
        $impression   = Categorie::where('nom', 'Impressions')->first()->id;
        $accessoire   = Categorie::where('nom', 'Imprimantes 3D')->first()->id;
        $logiciel     = Categorie::where('nom', 'Logiciels')->first()->id;

        $f1 = Fournisseur::skip(0)->first()->id;
        $f2 = Fournisseur::skip(1)->first()->id ?? $f1;
        $f3 = Fournisseur::skip(2)->first()->id ?? $f1;

        $articles = [
            // ── Ordinateurs ──
            ['nom' => 'Ordinateur portable Dell Latitude',  'description' => 'Dell Latitude 5520, Core i5, 8Go RAM, 256Go SSD',  'categorie_id' => $ordinateurs,  'fournisseur_id' => $f1, 'quantite_stock' => 12, 'stock_minimum' => 3, 'prix_unitaire' => 450000],
            ['nom' => 'Ordinateur portable HP EliteBook',   'description' => 'HP EliteBook 840, Core i7, 16Go RAM, 512Go SSD',   'categorie_id' => $ordinateurs,  'fournisseur_id' => $f1, 'quantite_stock' => 8,  'stock_minimum' => 2, 'prix_unitaire' => 580000],
            ['nom' => 'PC Bureau Lenovo ThinkCentre',       'description' => 'Lenovo ThinkCentre M720, Core i5, 8Go, 1To HDD',   'categorie_id' => $ordinateurs,  'fournisseur_id' => $f2, 'quantite_stock' => 5,  'stock_minimum' => 2, 'prix_unitaire' => 320000],
            ['nom' => 'Serveur Dell PowerEdge T40',         'description' => 'Serveur tour, Xeon E-2224G, 16Go ECC, 1To',         'categorie_id' => $ordinateurs,  'fournisseur_id' => $f1, 'quantite_stock' => 2,  'stock_minimum' => 1, 'prix_unitaire' => 1200000],
            ['nom' => 'Tablette Samsung Galaxy Tab S7',     'description' => 'Samsung Galaxy Tab S7, 128Go, WiFi',                'categorie_id' => $ordinateurs,  'fournisseur_id' => $f3, 'quantite_stock' => 6,  'stock_minimum' => 2, 'prix_unitaire' => 280000],

            // ── Périphériques ──
            ['nom' => 'Écran Dell 24 pouces',               'description' => 'Moniteur Dell P2422H, Full HD, IPS',                'categorie_id' => $peripherique, 'fournisseur_id' => $f1, 'quantite_stock' => 15, 'stock_minimum' => 4, 'prix_unitaire' => 120000],
            ['nom' => 'Clavier sans fil Logitech MK470',    'description' => 'Logitech MK470, clavier + souris sans fil',         'categorie_id' => $peripherique, 'fournisseur_id' => $f2, 'quantite_stock' => 20, 'stock_minimum' => 5, 'prix_unitaire' => 25000],
            ['nom' => 'Souris optique HP X500',             'description' => 'Souris filaire HP X500, USB',                       'categorie_id' => $peripherique, 'fournisseur_id' => $f2, 'quantite_stock' => 30, 'stock_minimum' => 8, 'prix_unitaire' => 8000],
            ['nom' => 'Webcam Logitech C920',               'description' => 'Webcam HD 1080p, micro intégré, USB',               'categorie_id' => $peripherique, 'fournisseur_id' => $f3, 'quantite_stock' => 3,  'stock_minimum' => 2, 'prix_unitaire' => 45000],
            ['nom' => 'Casque audio Sony WH-1000XM4',       'description' => 'Casque Bluetooth réduction de bruit active',        'categorie_id' => $peripherique, 'fournisseur_id' => $f3, 'quantite_stock' => 4,  'stock_minimum' => 2, 'prix_unitaire' => 95000],

            // ── Réseaux ──
            ['nom' => 'Switch TP-Link 8 ports',             'description' => 'Switch réseau 8 ports Gigabit TL-SG108',            'categorie_id' => $reseau,       'fournisseur_id' => $f2, 'quantite_stock' => 5,  'stock_minimum' => 2, 'prix_unitaire' => 18000],
            ['nom' => 'Routeur WiFi 6 TP-Link Archer',      'description' => 'Routeur WiFi 6 AX1500 dual band',                   'categorie_id' => $reseau,       'fournisseur_id' => $f2, 'quantite_stock' => 4,  'stock_minimum' => 1, 'prix_unitaire' => 35000],
            ['nom' => 'Câble réseau RJ45 Cat6 10m',         'description' => 'Câble Ethernet Cat6 blindé 10 mètres',              'categorie_id' => $reseau,       'fournisseur_id' => $f3, 'quantite_stock' => 30, 'stock_minimum' => 8, 'prix_unitaire' => 3000],
            ['nom' => 'Point d\'accès WiFi Ubiquiti',       'description' => 'UniFi AP AC Lite, WiFi 802.11ac dual band',         'categorie_id' => $reseau,       'fournisseur_id' => $f1, 'quantite_stock' => 3,  'stock_minimum' => 1, 'prix_unitaire' => 55000],

            // ── Stockage ──
            ['nom' => 'Disque SSD 256Go Samsung',           'description' => 'SSD Samsung 870 EVO 256Go SATA',                    'categorie_id' => $stockage,     'fournisseur_id' => $f2, 'quantite_stock' => 18, 'stock_minimum' => 5, 'prix_unitaire' => 35000],
            ['nom' => 'Disque dur 1To Seagate',             'description' => 'HDD Seagate Barracuda 1To 7200RPM',                 'categorie_id' => $stockage,     'fournisseur_id' => $f2, 'quantite_stock' => 12, 'stock_minimum' => 4, 'prix_unitaire' => 28000],
            ['nom' => 'Clé USB 64Go SanDisk',               'description' => 'Clé USB 3.0 SanDisk Ultra 64Go',                   'categorie_id' => $stockage,     'fournisseur_id' => $f3, 'quantite_stock' => 35, 'stock_minimum' => 10,'prix_unitaire' => 6000],
            ['nom' => 'Disque dur externe 2To',             'description' => 'WD Elements Portable 2To USB 3.0',                  'categorie_id' => $stockage,     'fournisseur_id' => $f2, 'quantite_stock' => 8,  'stock_minimum' => 2, 'prix_unitaire' => 55000],

            // ── Composants ──
            ['nom' => 'RAM DDR4 8Go Kingston',              'description' => 'Barrette RAM DDR4 8Go 3200MHz Kingston',            'categorie_id' => $composant,    'fournisseur_id' => $f2, 'quantite_stock' => 25, 'stock_minimum' => 8, 'prix_unitaire' => 22000],
            ['nom' => 'Processeur Intel Core i5-11400',     'description' => 'Intel Core i5-11400, 6 cœurs, 2.6GHz',              'categorie_id' => $composant,    'fournisseur_id' => $f1, 'quantite_stock' => 7,  'stock_minimum' => 2, 'prix_unitaire' => 120000],
            ['nom' => 'Carte mère ASUS Prime B560M',        'description' => 'ASUS Prime B560M-A, socket LGA1200',                'categorie_id' => $composant,    'fournisseur_id' => $f1, 'quantite_stock' => 4,  'stock_minimum' => 2, 'prix_unitaire' => 85000],
            ['nom' => 'Alimentation Corsair 550W',          'description' => 'Corsair CV550, 80+ Bronze, semi-modulaire',         'categorie_id' => $composant,    'fournisseur_id' => $f2, 'quantite_stock' => 6,  'stock_minimum' => 2, 'prix_unitaire' => 45000],

            // ── Impression ──
            ['nom' => 'Imprimante HP LaserJet Pro',         'description' => 'HP LaserJet Pro M404dn, recto-verso automatique',   'categorie_id' => $impression,   'fournisseur_id' => $f1, 'quantite_stock' => 4,  'stock_minimum' => 1, 'prix_unitaire' => 185000],
            ['nom' => 'Cartouche HP 650 Noir',              'description' => 'Cartouche d\'encre HP 650 noire, 360 pages',        'categorie_id' => $impression,   'fournisseur_id' => $f3, 'quantite_stock' => 3,  'stock_minimum' => 5, 'prix_unitaire' => 12000],
            ['nom' => 'Cartouche HP 650 Couleur',           'description' => 'Cartouche d\'encre HP 650 couleur, 200 pages',      'categorie_id' => $impression,   'fournisseur_id' => $f3, 'quantite_stock' => 2,  'stock_minimum' => 5, 'prix_unitaire' => 15000],
            ['nom' => 'Ramette papier A4 80g',              'description' => 'Ramette 500 feuilles A4 80g/m²',                    'categorie_id' => $impression,   'fournisseur_id' => $f3, 'quantite_stock' => 20, 'stock_minimum' => 5, 'prix_unitaire' => 3500],

            // ── Accessoires ──
            ['nom' => 'Sac laptop 15 pouces',               'description' => 'Sac de transport rembourré pour laptop 15.6"',      'categorie_id' => $accessoire,   'fournisseur_id' => $f3, 'quantite_stock' => 10, 'stock_minimum' => 3, 'prix_unitaire' => 12000],
            ['nom' => 'Support réglable pour laptop',       'description' => 'Support ergonomique aluminium réglable',            'categorie_id' => $accessoire,   'fournisseur_id' => $f3, 'quantite_stock' => 8,  'stock_minimum' => 2, 'prix_unitaire' => 15000],
            ['nom' => 'Multiprise 6 prises parasurtenseur', 'description' => 'Multiprise avec parasurtenseur, câble 2m',          'categorie_id' => $accessoire,   'fournisseur_id' => $f2, 'quantite_stock' => 12, 'stock_minimum' => 3, 'prix_unitaire' => 8500],
            ['nom' => 'Onduleur APC 650VA',                 'description' => 'UPS APC Back-UPS 650VA, 230V',                      'categorie_id' => $accessoire,   'fournisseur_id' => $f1, 'quantite_stock' => 3,  'stock_minimum' => 1, 'prix_unitaire' => 95000],
            ['nom' => 'Tapis de souris XXL',                'description' => 'Tapis de souris grand format 80x30cm',              'categorie_id' => $accessoire,   'fournisseur_id' => $f3, 'quantite_stock' => 15, 'stock_minimum' => 4, 'prix_unitaire' => 5000],

            // ── Logiciels ──
            ['nom' => 'Licence Windows 11 Pro',             'description' => 'Licence Microsoft Windows 11 Professionnel',        'categorie_id' => $logiciel,     'fournisseur_id' => $f1, 'quantite_stock' => 10, 'stock_minimum' => 3, 'prix_unitaire' => 95000],
            ['nom' => 'Licence Microsoft Office 2021',      'description' => 'Microsoft Office 2021 Home & Business',             'categorie_id' => $logiciel,     'fournisseur_id' => $f1, 'quantite_stock' => 8,  'stock_minimum' => 2, 'prix_unitaire' => 120000],
            ['nom' => 'Antivirus Kaspersky 1 an',           'description' => 'Kaspersky Internet Security, 1 poste, 1 an',        'categorie_id' => $logiciel,     'fournisseur_id' => $f2, 'quantite_stock' => 15, 'stock_minimum' => 5, 'prix_unitaire' => 25000],
        ];

        foreach ($articles as $a) {
            Article::create($a);
        }
    }
}