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
            [
                'name' => 'Николай Соколов',
                'email' => 'nikolai@example.com',
                'phone' => '+7 (900) 777-88-99',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ольга Морозова',
                'email' => 'olga.m@example.com',
                'phone' => '+7 (900) 888-99-00',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Сергей Павлов',
                'email' => 'sergey.p@example.com',
                'phone' => '+7 (900) 999-00-11',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Юлия Козлова',
                'email' => 'yulia@example.com',
                'phone' => '+7 (900) 000-11-22',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Андрей Воробьев',
                'email' => 'andrey@example.com',
                'phone' => '+7 (900) 123-45-67',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Светлана Новикова',
                'email' => 'svetlana@example.com',
                'phone' => '+7 (900) 234-56-78',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Артем Мельников',
                'email' => 'artem@example.com',
                'phone' => '+7 (900) 345-67-89',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ирина Степанова',
                'email' => 'irina@example.com',
                'phone' => '+7 (900) 456-78-90',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Максим Зайцев',
                'email' => 'maxim@example.com',
                'phone' => '+7 (900) 567-89-01',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Елена Полякова',
                'email' => 'elena.p@example.com',
                'phone' => '+7 (900) 678-90-12',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Виктор Титов',
                'email' => 'viktor@example.com',
                'phone' => '+7 (900) 789-01-23',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Анна Романова',
                'email' => 'anna.r@example.com',
                'phone' => '+7 (900) 890-12-34',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Денис Соловьев',
                'email' => 'denis@example.com',
                'phone' => '+7 (900) 901-23-45',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Оксана Белова',
                'email' => 'oksana@example.com',
                'phone' => '+7 (900) 012-34-56',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Павел Григорьев',
                'email' => 'pavel@example.com',
                'phone' => '+7 (900) 121-12-12',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
