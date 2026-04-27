<?php

namespace Database\Seeders;

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Création du compte admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@stockcentre.bj'],
            [
                'nom'      => 'Administrateur',
                'prenom'   => 'Super',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'is_active' => true,
            ]
        );

        // Génère 5 codes d'invitation pour les premiers utilisateurs
        for ($i = 0; $i < 5; $i++) {
            InvitationCode::create([
                'code'       => InvitationCode::generer(),
                'created_by' => $admin->id,
                'expires_at' => now()->addDays(30),
            ]);
        }

        $this->command->info('✅ Admin créé : admin@stockcentre.bj / Admin@1234');
        $this->command->info('✅ 5 codes d\'invitation générés.');
        $this->command->table(
            ['Code', 'Expire le'],
            InvitationCode::where('created_by', $admin->id)
                ->get()
                ->map(fn($c) => [$c->code, $c->expires_at->format('d/m/Y')])
                ->toArray()
        );
    }
}