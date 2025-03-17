<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Thuốc kháng sinh'],
            ['name' => 'Thuốc giảm đau'],
            ['name' => 'Thuốc hạ sốt'],
            ['name' => 'Thuốc chống viêm'],
            ['name' => 'Thuốc tiêu hóa'],
            ['name' => 'Thuốc tim mạch'],
            ['name' => 'Thuốc huyết áp'],
            ['name' => 'Thuốc tiểu đường'],
            ['name' => 'Vitamin và khoáng chất'],
            ['name' => 'Thuốc da liễu'],
        ];

        DB::table('product_categories')->insert($categories);
    }
}
