<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Seed the groups table.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Группа1',
                'expire_hours' => 1
            ],
            [
                'name' => 'Группа2',
                'expire_hours' => 2
            ],
        ];

        DB::table('groups')->insert($groups);
    }
}
