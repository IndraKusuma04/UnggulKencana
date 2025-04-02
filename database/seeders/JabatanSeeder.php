<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'ADMIN',
            'OWNER',
            'PEGAWAI'
        ];

        foreach ($data as $value) {
            Jabatan::create([
                'jabatan'   => $value,
                'status'    => 1
            ]);
        }
    }
}
