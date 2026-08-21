<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::factory()->member()->count(5)->create();

        foreach ($members as $member) {
            $member->assignRole('member');
        }
    }
}
