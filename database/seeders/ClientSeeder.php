<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Иван Петров',
                'email' => 'ivan@example.com',
                'phone' => '+7 (900) 111-22-33',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Мария Иванова',
                'email' => 'maria@example.com',
                'phone' => '+7 (900) 222-33-44',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Алексей Сидоров',
                'email' => 'alexey@example.com',
                'phone' => '+7 (900) 333-44-55',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Екатерина Смирнова',
                'email' => 'katya@example.com',
                'phone' => '+7 (900) 444-55-66',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Дмитрий Кузнецов',
                'email' => 'dima@example.com',
                'phone' => '+7 (900) 555-66-77',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Татьяна Лебедева',
                'email' => 'tanya@example.com',
                'phone' => '+7 (900) 666-77-88',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
