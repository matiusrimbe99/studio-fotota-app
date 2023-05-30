<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Contact::create([
            'account_number' => 'requestaccountnumber',
            'name_on_account' => 'requestnameonaccount',
            'bank_name' => 'requestbankname',
            'method_order' => 'requestmethodorder',
            'whatsapp' => 'requestwhatsapp',
            'facebook' => 'requestfacebook',
            'instagram' => 'requestinstagram',
        ]);
    }
}
