<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::create([
            'name' => 'João da Silva',
            'email' => 'joao@email.com',
            'phone' => '11999999999',
            'zip_code' => '01001-000',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'address' => 'Praça da Sé',
            'number' => '100'
        ]);
    }
}
