<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            // Salon 1 - Хотьково
            [
                'salon_id' => 1,
                'type' => 'phone',
                'value' => '+7 (496) 543-00-00',
            ],
            [
                'salon_id' => 1,
                'type' => 'email',
                'value' => 'khotkovo@choco-salon.ru',
            ],
            [
                'salon_id' => 1,
                'type' => 'instagram',
                'value' => '@choco_khotkovo',
            ],
            
            // Salon 2 - Посад (Центр)
            [
                'salon_id' => 2,
                'type' => 'phone',
                'value' => '+7 (496) 540-00-00',
            ],
            [
                'salon_id' => 2,
                'type' => 'email',
                'value' => 'posad.center@choco-salon.ru',
            ],
            [
                'salon_id' => 2,
                'type' => 'instagram',
                'value' => '@choco_posad_center',
            ],
            
            // Salon 3 - Посад (Запад)
            [
                'salon_id' => 3,
                'type' => 'phone',
                'value' => '+7 (496) 549-00-00',
            ],
            [
                'salon_id' => 3,
                'type' => 'email',
                'value' => 'posad.west@choco-salon.ru',
            ],
            [
                'salon_id' => 3,
                'type' => 'instagram',
                'value' => '@choco_posad_west',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
