<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\role;
use App\Models\cart;
use App\Models\point;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => "Valorant",
            "slug" => 'valorant',
            'image' => ''
        ]);
        Category::create([
            'name' => "Mobile Legends",
            "slug" => 'mobile-legend',
            'image' => ''
        ]);
        role::create([
            'name' => "Coach",
            "slug" => 'coach'
        ]);
        role::create([
            'name' => "Player",
            "slug" => 'player'
        ]);
        role::create([
            'name' => "Admin",
            "slug" => 'admin'
        ]);
        role::create([
            'name' => "User",
            "slug" => 'user'
        ]);
        User::create([
            'name' => 'Venicia Setiani',
            'price' => 100.00,
            'username' => 'Venicia s',
            'email' => "vs@gmail.com",
            'excerpt' => "halo semua nama aku venicia",
            "body" => "halo semua nama aku venicia, yuk main bareng guys aku ini anaknya mage banget jadi bisa buat gendong kalian! cus order",
            'category_id' => 2,
            'role_id' => 1,
            'idcardnumber' => 1234567890123451,
            'norekening' => 1234567890123451,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password')
        ]);
        User::create([
            'name' => 'Leonard Christ',
            'username' => 'Lele89',
            'price' => 150.00,
            'email' => "lele@gmail.com",
            'excerpt' => "halo semua nama aku leon",
            "body" => "halo semua nama aku venicia, yuk main bareng guys aku ini anaknya duelist banget jadi bisa buat gendong kalian! cus order",
            'category_id' => 1,
            'role_id' => 2,
            'idcardnumber' => 1234567890123452,
            'norekening' => 1234567890123452,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password')
        ]);
        User::create([
            'name' => 'Reyna viper',
            'username' => 'Reyna viper',
            'price' => 123.00,
            'email' => "rey@gmail.com",
            'excerpt' => "halo semua nama aku reyna",
            "body" => "halo semua nama aku venicia, yuk main bareng guys aku ini anaknya marksman banget jadi bisa buat gendong kalian! cus order",
            'category_id' => 2,
            'role_id' => 3,
            'idcardnumber' => 1234567890123453,
            'norekening' => 1234567890123453,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password')
        ]);
        User::create([
            'name' => 'Sova',
            'username' => 'Sovarey',
            'price' => null,
            'email' => "s@gmail.com",
            'excerpt' => null,
            "body" => null,
            'category_id' => 2,
            'role_id' => 3,
            'idcardnumber'=>1234567890123454,
            'norekening'=>1234567890123454,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password')
        ]);
        User::create([
            'name' => 'alexnc',
            'username' => 'alex',
            'price' => null,
            'email' => "alexsantoso278@gmail.com",
            'excerpt' => null,
            "body" => null,
            'category_id' => 2,
            'role_id' => 4,
            'idcardnumber'=>1234567890123454,
            'norekening'=>1234567890123454,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password')
        ]);
        User::create([
            'name' => 'pujo',
            'username' => 'pujos',
            'price' => null,
            'email' => "pujo@gmail.com",
            'excerpt' => null,
            "body" => null,
            'category_id' => 2,
            'role_id' => 4,
            'idcardnumber'=>1234567890123454,
            'norekening'=>1234567890123454,
            'idcardstatcode' => "APV",
            "password" => bcrypt('password123')
        ]);
        // cart::create([
        //     'user_id' => 1,
        //     'buyer_id' => 4,
        //     'quantity'
        // ]);
    }
}
