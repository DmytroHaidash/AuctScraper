<?php

namespace Database\Seeders;

use App\Models\TypeAuction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeAuctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeAuction::query()->create([
           'name' => 'LiveAuctioneers'
        ]);
    }
}
