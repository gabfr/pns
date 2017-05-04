<?php

use Illuminate\Database\Seeder;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Gabriel Ferreira Rosalino',
            'email' => 'eu@gabrielf.com',
            'is_active' => true
        ]);

        $user->update(['password' => bcrypt('secret')]);
    }
}
