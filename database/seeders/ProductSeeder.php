<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Thuốc kháng sinh (category_id: 1)
            [
                'name' => 'Amoxicillin',
                'category_id' => 1,
                'unit' => 'Viên',
                'price' => 120000,
                'description' => 'Thuốc kháng sinh điều trị nhiễm khuẩn đường hô hấp, tiết niệu, da và mô mềm.',
                'status' => true,
            ],
            [
                'name' => 'Cefixime',
                'category_id' => 1,
                'unit' => 'Viên',
                'price' => 150000,
                'description' => 'Thuốc kháng sinh điều trị nhiễm khuẩn đường hô hấp, tiết niệu.',
                'status' => true,
            ],
            [
                'name' => 'Azithromycin',
                'category_id' => 1,
                'unit' => 'Viên',
                'price' => 180000,
                'description' => 'Thuốc kháng sinh nhóm macrolide điều trị nhiễm khuẩn đường hô hấp, da và mô mềm.',
                'status' => true,
            ],

            // Thuốc giảm đau (category_id: 2)
            [
                'name' => 'Paracetamol',
                'category_id' => 2,
                'unit' => 'Viên',
                'price' => 50000,
                'description' => 'Thuốc giảm đau, hạ sốt thông dụng.',
                'status' => true,
            ],
            [
                'name' => 'Ibuprofen',
                'category_id' => 2,
                'unit' => 'Viên',
                'price' => 70000,
                'description' => 'Thuốc giảm đau, kháng viêm không steroid.',
                'status' => true,
            ],

            // Thuốc hạ sốt (category_id: 3)
            [
                'name' => 'Efferalgan',
                'category_id' => 3,
                'unit' => 'Viên sủi',
                'price' => 85000,
                'description' => 'Thuốc hạ sốt dạng viên sủi, chứa paracetamol.',
                'status' => true,
            ],

            // Thuốc chống viêm (category_id: 4)
            [
                'name' => 'Methylprednisolone',
                'category_id' => 4,
                'unit' => 'Viên',
                'price' => 120000,
                'description' => 'Thuốc chống viêm corticosteroid.',
                'status' => true,
            ],
            [
                'name' => 'Diclofenac',
                'category_id' => 4,
                'unit' => 'Viên',
                'price' => 65000,
                'description' => 'Thuốc kháng viêm không steroid, giảm đau.',
                'status' => true,
            ],

            // Thuốc tiêu hóa (category_id: 5)
            [
                'name' => 'Omeprazole',
                'category_id' => 5,
                'unit' => 'Viên',
                'price' => 95000,
                'description' => 'Thuốc ức chế bơm proton, điều trị loét dạ dày, trào ngược dạ dày thực quản.',
                'status' => true,
            ],
            [
                'name' => 'Mebeverine',
                'category_id' => 5,
                'unit' => 'Viên',
                'price' => 110000,
                'description' => 'Thuốc điều trị hội chứng ruột kích thích.',
                'status' => true,
            ],

            // Thuốc tim mạch (category_id: 6)
            [
                'name' => 'Aspirin',
                'category_id' => 6,
                'unit' => 'Viên',
                'price' => 60000,
                'description' => 'Thuốc chống kết tập tiểu cầu, dự phòng các bệnh tim mạch.',
                'status' => true,
            ],
            [
                'name' => 'Atorvastatin',
                'category_id' => 6,
                'unit' => 'Viên',
                'price' => 130000,
                'description' => 'Thuốc hạ lipid máu, điều trị tăng cholesterol.',
                'status' => true,
            ],

            // Thuốc huyết áp (category_id: 7)
            [
                'name' => 'Amlodipine',
                'category_id' => 7,
                'unit' => 'Viên',
                'price' => 75000,
                'description' => 'Thuốc chẹn kênh canxi, điều trị tăng huyết áp.',
                'status' => true,
            ],
            [
                'name' => 'Losartan',
                'category_id' => 7,
                'unit' => 'Viên',
                'price' => 90000,
                'description' => 'Thuốc đối kháng thụ thể angiotensin II, điều trị tăng huyết áp.',
                'status' => true,
            ],

            // Thuốc tiểu đường (category_id: 8)
            [
                'name' => 'Metformin',
                'category_id' => 8,
                'unit' => 'Viên',
                'price' => 65000,
                'description' => 'Thuốc điều trị đái tháo đường type 2.',
                'status' => true,
            ],
            [
                'name' => 'Gliclazide',
                'category_id' => 8,
                'unit' => 'Viên',
                'price' => 85000,
                'description' => 'Thuốc hạ đường huyết nhóm sulfonylurea.',
                'status' => true,
            ],

            // Vitamin và khoáng chất (category_id: 9)
            [
                'name' => 'Vitamin C',
                'category_id' => 9,
                'unit' => 'Viên',
                'price' => 55000,
                'description' => 'Vitamin C bổ sung, tăng cường miễn dịch.',
                'status' => true,
            ],
            [
                'name' => 'Vitamin D3',
                'category_id' => 9,
                'unit' => 'Viên',
                'price' => 70000,
                'description' => 'Vitamin D3 bổ sung, hỗ trợ hấp thu canxi.',
                'status' => true,
            ],
            [
                'name' => 'Calcium',
                'category_id' => 9,
                'unit' => 'Viên',
                'price' => 65000,
                'description' => 'Bổ sung canxi, phòng ngừa loãng xương.',
                'status' => true,
            ],

            // Thuốc da liễu (category_id: 10)
            [
                'name' => 'Mometasone',
                'category_id' => 10,
                'unit' => 'Tuýp',
                'price' => 120000,
                'description' => 'Kem bôi chứa corticosteroid, điều trị viêm da.',
                'status' => true,
            ],
            [
                'name' => 'Clotrimazole',
                'category_id' => 10,
                'unit' => 'Tuýp',
                'price' => 85000,
                'description' => 'Kem bôi kháng nấm, điều trị nấm da.',
                'status' => true,
            ],
        ];

        // Tạo sản phẩm
        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Tạo lô sản phẩm cho mỗi sản phẩm
            $this->createBatchesForProduct($product);
        }
    }

    /**
     * Tạo các lô sản phẩm cho một sản phẩm
     */
    private function createBatchesForProduct($product)
    {
        // Tạo 2-3 lô cho mỗi sản phẩm
        $batchCount = rand(2, 3);

        for ($i = 1; $i <= $batchCount; $i++) {
            // Tạo mã lô dựa trên Mã sản phẩm
            $batchNumber = 'BATCH-' . rand(1000, 9999);

            // Ngày sản xuất: 6-18 tháng trước
            $manufacturingDate = Carbon::now()->subMonths(rand(6, 18))->format('Y-m-d');

            // Hạn sử dụng: 1-3 năm sau ngày sản xuất
            $expiryDate = Carbon::parse($manufacturingDate)->addYears(rand(1, 3))->format('Y-m-d');

            // Số lượng trong lô
            $quantity = rand(50, 300);

            // Giá nhập có thể thay đổi một chút so với giá nhập chuẩn
            $importPrice = 50000 * (1 + (rand(-5, 5) / 100));

            // Trạng thái lô
            $status = Carbon::parse($expiryDate)->isPast() ? 'expired' : 'active';

            // Tạo lô
            ProductBatch::create([
                'product_id' => $product->id,
                'batch_number' => $batchNumber,
                'manufacturing_date' => $manufacturingDate,
                'expiry_date' => $expiryDate,
                'quantity' => $quantity,
                'import_price' => $importPrice,
                'status' => $status,
            ]);
        }
    }
}
