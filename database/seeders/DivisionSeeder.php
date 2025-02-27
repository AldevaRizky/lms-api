<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user yang bisa dijadikan manager
        if (User::count() === 0) {
            $this->command->info('Tidak ada data user. Harap jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Ambil user secara acak untuk dijadikan manager
        $managerIds = User::pluck('id')->toArray();

        // Data divisi yang akan diinput
        $divisions = [
            [
                'name' => 'Human Resources',
                'description' => 'Mengurus kebutuhan karyawan dan administrasi SDM',
                'manager_id' => $managerIds[array_rand($managerIds)],
            ],
            [
                'name' => 'Finance',
                'description' => 'Mengurus keuangan perusahaan',
                'manager_id' => $managerIds[array_rand($managerIds)],
            ],
            [
                'name' => 'IT Support',
                'description' => 'Mendukung kebutuhan teknologi informasi perusahaan',
                'manager_id' => $managerIds[array_rand($managerIds)],
            ],
            [
                'name' => 'Marketing',
                'description' => 'Mengurus pemasaran dan branding perusahaan',
                'manager_id' => $managerIds[array_rand($managerIds)],
            ],
            [
                'name' => 'Operations',
                'description' => 'Mengatur operasional perusahaan sehari-hari',
                'manager_id' => $managerIds[array_rand($managerIds)],
            ],
        ];

        // Insert data ke database
        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
