<?php

namespace Database\Seeders;

use App\Models\Kondisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KondisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'BAIK',
            'KUSAM',
            'RUSAK'
        ];

        foreach ($data as $value) {
            Kondisi::create([
                'kondisi'   => $value,
                'status'    => 1
            ]);
        }
    }
}
