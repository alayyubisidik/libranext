<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Payment::truncate();
        Fine::truncate();
        Borrowing::truncate();
        Book::truncate();
        Category::truncate();

        User::whereHas('roles', fn ($q) => $q->where('name', 'member'))->forceDelete();
        User::where('email', 'admin@libranext.id')->forceDelete();

        DB::table('activity_log')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            UserSeeder::class,
            BorrowingSeeder::class,
        ]);
    }
}
