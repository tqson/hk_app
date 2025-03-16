<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'full_name' => 'Quản Trị Viên',
            'date_of_birth' => '1990-01-15',
            'address' => 'Số 123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh',
            'phone' => '0901234567',
            'email' => 'admin@gmail.com',
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '1234567890',
            'created_at' => Carbon::now(),
        ]);

        // Create 9 more users with Vietnamese information
        $users = [
            [
                'username' => 'nguyenvan',
                'password' => Hash::make('123456'),
                'full_name' => 'Nguyễn Văn An',
                'date_of_birth' => '1992-05-20',
                'address' => 'Số 45 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'phone' => '0912345678',
                'email' => 'nguyenvanan@gmail.com',
                'bank_name' => 'Agribank',
                'bank_account_number' => '9876543210',
            ],
            [
                'username' => 'tranthihuong',
                'password' => Hash::make('123456'),
                'full_name' => 'Trần Thị Hương',
                'date_of_birth' => '1988-11-12',
                'address' => 'Số 78 Đường Trần Hưng Đạo, Quận Hoàn Kiếm, Hà Nội',
                'phone' => '0987654321',
                'email' => 'huongtran@gmail.com',
                'bank_name' => 'BIDV',
                'bank_account_number' => '1122334455',
            ],
            [
                'username' => 'lephuong',
                'password' => Hash::make('123456'),
                'full_name' => 'Lê Thị Phương',
                'date_of_birth' => '1995-03-25',
                'address' => 'Số 56 Đường Lý Thường Kiệt, Quận Hải Châu, Đà Nẵng',
                'phone' => '0977123456',
                'email' => 'lephuong@gmail.com',
                'bank_name' => 'Techcombank',
                'bank_account_number' => '5566778899',
            ],
            [
                'username' => 'phamtuan',
                'password' => Hash::make('123456'),
                'full_name' => 'Phạm Anh Tuấn',
                'date_of_birth' => '1985-07-30',
                'address' => 'Số 123 Đường Trần Phú, TP. Nha Trang, Khánh Hòa',
                'phone' => '0933789456',
                'email' => 'phamtuan@gmail.com',
                'bank_name' => 'VPBank',
                'bank_account_number' => '6677889900',
            ],
            [
                'username' => 'hoangminh',
                'password' => Hash::make('123456'),
                'full_name' => 'Hoàng Minh Đức',
                'date_of_birth' => '1991-12-05',
                'address' => 'Số 89 Đường Nguyễn Trãi, Quận Thanh Xuân, Hà Nội',
                'phone' => '0966123789',
                'email' => 'hoangduc@gmail.com',
                'bank_name' => 'MBBank',
                'bank_account_number' => '1122334455',
            ],
            [
                'username' => 'vothihoa',
                'password' => Hash::make('123456'),
                'full_name' => 'Võ Thị Hoa',
                'date_of_birth' => '1993-09-18',
                'address' => 'Số 234 Đường 3/2, Quận 10, TP. Hồ Chí Minh',
                'phone' => '0944567890',
                'email' => 'vohoa@gmail.com',
                'bank_name' => 'ACB',
                'bank_account_number' => '9988776655',
            ],
            [
                'username' => 'dangquang',
                'password' => Hash::make('123456'),
                'full_name' => 'Đặng Quang Huy',
                'date_of_birth' => '1987-04-22',
                'address' => 'Số 67 Đường Lê Duẩn, TP. Huế, Thừa Thiên Huế',
                'phone' => '0955678123',
                'email' => 'quanghuy@gmail.com',
                'bank_name' => 'Sacombank',
                'bank_account_number' => '5544332211',
            ],
            [
                'username' => 'ngothithanh',
                'password' => Hash::make('123456'),
                'full_name' => 'Ngô Thị Thanh',
                'date_of_birth' => '1994-08-10',
                'address' => 'Số 45 Đường Trần Duy Hưng, Quận Cầu Giấy, Hà Nội',
                'phone' => '0922345678',
                'email' => 'ngothanh@gmail.com',
                'bank_name' => 'TPBank',
                'bank_account_number' => '1234987650',
            ],
            [
                'username' => 'buivannam',
                'password' => Hash::make('123456'),
                'full_name' => 'Bùi Văn Nam',
                'date_of_birth' => '1989-06-15',
                'address' => 'Số 123 Đường Nguyễn Văn Linh, Quận 7, TP. Hồ Chí Minh',
                'phone' => '0911234987',
                'email' => 'buinam@gmail.com',
                'bank_name' => 'HDBank',
                'bank_account_number' => '6677889900',
            ],
        ];

        foreach ($users as $userData) {
            User::create(array_merge($userData, ['created_at' => Carbon::now()]));
        }
    }
}
