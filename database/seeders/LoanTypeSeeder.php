<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoanType::create([
            'name' => 'Umum',
            'interest_rate' => 1
        ]);

        LoanType::create([
            'name' => 'Khusus',
            'interest_rate' => 2
        ]);
    }
}
