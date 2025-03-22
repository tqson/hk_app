<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Công ty TNHH Dược phẩm Minh Châu',
                'tax_code' => '0123456789',
                'address' => '123 Nguyễn Văn Linh, Quận 7, TP.HCM',
                'phone' => '0901234567',
                'email' => 'info@minhchau.com',
                'contact_person' => 'Nguyễn Văn A',
            ],
            [
                'name' => 'Công ty CP Dược phẩm Imexpharm',
                'tax_code' => '0987654321',
                'address' => '456 Lê Lợi, Quận 1, TP.HCM',
                'phone' => '0909876543',
                'email' => 'contact@imexpharm.com',
                'contact_person' => 'Trần Thị B',
            ],
            [
                'name' => 'Công ty TNHH Dược phẩm Trang Minh',
                'tax_code' => '0123498765',
                'address' => '789 Điện Biên Phủ, Quận 3, TP.HCM',
                'phone' => '0912345678',
                'email' => 'sales@trangminh.com',
                'contact_person' => 'Lê Văn C',
            ],
            [
                'name' => 'Công ty CP Dược phẩm OPC',
                'tax_code' => '0567891234',
                'address' => '321 Nguyễn Trãi, Quận 5, TP.HCM',
                'phone' => '0898765432',
                'email' => 'info@opcpharma.com',
                'contact_person' => 'Phạm Thị D',
            ],
            [
                'name' => 'Công ty TNHH Dược phẩm Pharmedic',
                'tax_code' => '0345678912',
                'address' => '654 Cách Mạng Tháng 8, Quận 10, TP.HCM',
                'phone' => '0976543210',
                'email' => 'contact@pharmedic.com',
                'contact_person' => 'Hoàng Văn E',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
