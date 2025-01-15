<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PersonalClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientRepository = new ClientRepository();

        $clientRepository->createPersonalAccessClient(
            null,
            'Laravel Personal Access Client',
            'http://127.0.0.1:8000'
        );
    }
}
