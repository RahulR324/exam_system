<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $count = $db->table('admins')->countAllResults();

        if ($count === 0) {
            $db->table('admins')->insert([
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT)
            ]);

            echo "Admin seeded successfully!\n";
        } else {
            echo "Admins already exist. Skipping seed.\n";
        }
    }
}