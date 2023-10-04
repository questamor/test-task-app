<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Иванов',
                'email' => 'info@datainlife.ru'
            ],
            [
                'name' => 'Петров',
                'email' => 'job@datainlife.ru'
            ],
        ];

        DB::table('users')->insert($users);

    }
}
