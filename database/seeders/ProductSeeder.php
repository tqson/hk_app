<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            // Thuốc kháng sinh (category_id: 1)
            [
                'name' => 'Amoxicillin',
                'category_id' => 1,
                'unit' => 'Viên',
                'batch_number' => 'AMX'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(100, 500),
            ],
            [
                'name' => 'Cefixime',
                'category_id' => 1,
                'unit' => 'Viên',
                'batch_number' => 'CFX'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(100, 500),
            ],
            [
                'name' => 'Azithromycin',
                'category_id' => 1,
                'unit' => 'Viên',
                'batch_number' => 'AZT'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(100, 500),
            ],

            // Thuốc giảm đau (category_id: 2)
            [
                'name' => 'Paracetamol',
                'category_id' => 2,
                'unit' => 'Viên',
                'batch_number' => 'PCT'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(200, 800),
            ],
            [
                'name' => 'Ibuprofen',
                'category_id' => 2,
                'unit' => 'Viên',
                'batch_number' => 'IBP'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(200, 800),
            ],

            // Thuốc hạ sốt (category_id: 3)
            [
                'name' => 'Efferalgan',
                'category_id' => 3,
                'unit' => 'Viên sủi',
                'batch_number' => 'EFG'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 600),
            ],

            // Thuốc chống viêm (category_id: 4)
            [
                'name' => 'Methylprednisolone',
                'category_id' => 4,
                'unit' => 'Viên',
                'batch_number' => 'MPD'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(100, 400),
            ],
            [
                'name' => 'Diclofenac',
                'category_id' => 4,
                'unit' => 'Viên',
                'batch_number' => 'DCF'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(100, 400),
            ],

            // Thuốc tiêu hóa (category_id: 5)
            [
                'name' => 'Omeprazole',
                'category_id' => 5,
                'unit' => 'Viên',
                'batch_number' => 'OMP'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],
            [
                'name' => 'Mebeverine',
                'category_id' => 5,
                'unit' => 'Viên',
                'batch_number' => 'MBV'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],

            // Thuốc tim mạch (category_id: 6)
            [
                'name' => 'Aspirin',
                'category_id' => 6,
                'unit' => 'Viên',
                'batch_number' => 'ASP'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(200, 600),
            ],
            [
                'name' => 'Atorvastatin',
                'category_id' => 6,
                'unit' => 'Viên',
                'batch_number' => 'ATV'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],

            // Thuốc huyết áp (category_id: 7)
            [
                'name' => 'Amlodipine',
                'category_id' => 7,
                'unit' => 'Viên',
                'batch_number' => 'AML'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],
            [
                'name' => 'Losartan',
                'category_id' => 7,
                'unit' => 'Viên',
                'batch_number' => 'LST'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],

            // Thuốc tiểu đường (category_id: 8)
            [
                'name' => 'Metformin',
                'category_id' => 8,
                'unit' => 'Viên',
                'batch_number' => 'MTF'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],
            [
                'name' => 'Gliclazide',
                'category_id' => 8,
                'unit' => 'Viên',
                'batch_number' => 'GLC'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(150, 500),
            ],

            // Vitamin và khoáng chất (category_id: 9)
            [
                'name' => 'Vitamin C',
                'category_id' => 9,
                'unit' => 'Viên',
                'batch_number' => 'VTC'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(300, 1000),
            ],
            [
                'name' => 'Vitamin D3',
                'category_id' => 9,
                'unit' => 'Viên',
                'batch_number' => 'VTD'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(300, 1000),
            ],
            [
                'name' => 'Calcium',
                'category_id' => 9,
                'unit' => 'Viên',
                'batch_number' => 'CAL'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => rand(300, 1000),
            ],

            // Thuốc da liễu (category_id: 10)
            [
                'name' => 'Mometasone',
                'category_id' => 10,
                'unit' => 'Tuýp',
                'batch_number' => 'MMT'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(50, 200),
            ],
            [
                'name' => 'Clotrimazole',
                'category_id' => 10,
                'unit' => 'Tuýp',
                'batch_number' => 'CTM'.rand(1000, 9999),
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => rand(50, 200),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
