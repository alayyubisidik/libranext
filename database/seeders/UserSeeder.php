<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@libranext.id'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'member_status'     => 'active',
                'joined_at'         => now()->subYears(2)->format('Y-m-d'),
                'remember_token'    => Str::random(10),
            ]
        );
        $admin->assignRole('admin');

        $members = [
            ['name' => 'Budi Santoso',     'email' => 'budi@libranext.id',    'phone' => '081234567801', 'address' => 'Jl. Merdeka No. 12, Bandung',             'date_of_birth' => '1995-03-14', 'member_code' => 'MBR-001'],
            ['name' => 'Siti Rahayu',      'email' => 'siti@libranext.id',    'phone' => '081234567802', 'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',       'date_of_birth' => '1998-07-22', 'member_code' => 'MBR-002'],
            ['name' => 'Ahmad Fauzi',      'email' => 'ahmad@libranext.id',   'phone' => '081234567803', 'address' => 'Jl. Diponegoro No. 8, Surabaya',           'date_of_birth' => '1993-11-05', 'member_code' => 'MBR-003'],
            ['name' => 'Dewi Kusuma',      'email' => 'dewi@libranext.id',    'phone' => '081234567804', 'address' => 'Jl. Gatot Subroto No. 77, Medan',          'date_of_birth' => '2000-01-30', 'member_code' => 'MBR-004'],
            ['name' => 'Rizky Pratama',    'email' => 'rizky@libranext.id',   'phone' => '081234567805', 'address' => 'Jl. Ahmad Yani No. 21, Yogyakarta',        'date_of_birth' => '1997-05-18', 'member_code' => 'MBR-005'],
            ['name' => 'Lina Marlina',     'email' => 'lina@libranext.id',    'phone' => '081234567806', 'address' => 'Jl. Imam Bonjol No. 3, Semarang',          'date_of_birth' => '1999-09-09', 'member_code' => 'MBR-006'],
            ['name' => 'Hendra Wijaya',    'email' => 'hendra@libranext.id',  'phone' => '081234567807', 'address' => 'Jl. Pahlawan No. 55, Makassar',            'date_of_birth' => '1992-12-25', 'member_code' => 'MBR-007'],
            ['name' => 'Nur Hidayah',      'email' => 'nur@libranext.id',     'phone' => '081234567808', 'address' => 'Jl. Hasanuddin No. 10, Palembang',         'date_of_birth' => '2001-04-11', 'member_code' => 'MBR-008'],
            ['name' => 'Fajar Ramadhan',   'email' => 'fajar@libranext.id',   'phone' => '081234567809', 'address' => 'Jl. Cut Nyak Dien No. 6, Pekanbaru',      'date_of_birth' => '1996-08-03', 'member_code' => 'MBR-009'],
            ['name' => 'Anggi Permata',    'email' => 'anggi@libranext.id',   'phone' => '081234567810', 'address' => 'Jl. Tuanku Imam No. 18, Padang',           'date_of_birth' => '2002-02-14', 'member_code' => 'MBR-010'],
            ['name' => 'Eko Prasetyo',     'email' => 'eko@libranext.id',     'phone' => '081234567811', 'address' => 'Jl. Raden Intan No. 33, Bandar Lampung',  'date_of_birth' => '1994-06-20', 'member_code' => 'MBR-011'],
            ['name' => 'Rina Wulandari',   'email' => 'rina@libranext.id',    'phone' => '081234567812', 'address' => 'Jl. Sultan Hasanuddin No. 9, Balikpapan', 'date_of_birth' => '1999-10-07', 'member_code' => 'MBR-012'],
        ];

        foreach ($members as $memberData) {
            $user = User::firstOrCreate(
                ['email' => $memberData['email']],
                array_merge($memberData, [
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'member_status'     => 'active',
                    'joined_at'         => now()->subMonths(rand(3, 24))->format('Y-m-d'),
                    'remember_token'    => Str::random(10),
                ])
            );
            $user->assignRole('member');
        }
    }
}
