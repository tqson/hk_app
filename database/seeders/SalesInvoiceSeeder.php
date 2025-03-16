<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesInvoice;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class SalesInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('vi_VN');
        $users = User::all()->pluck('id')->toArray();

        $startDate = Carbon::create(Carbon::now()->subYear()->year, 1, 1, 0, 0, 0);
        $endDate = Carbon::now();

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $invoicesPerDay = rand(3, 10);

            for ($i = 0; $i < $invoicesPerDay; $i++) {
                $totalAmount = $faker->numberBetween(100000, 5000000);

                SalesInvoice::create([
                    'user_id' => $faker->randomElement($users),
                    'total_amount' => $totalAmount,
                    'created_at' => $currentDate->copy()->setHour(rand(8, 20))->setMinute(rand(0, 59))->setSecond(rand(0, 59)),
                    'updated_at' => $currentDate->copy()->setHour(rand(8, 20))->setMinute(rand(0, 59))->setSecond(rand(0, 59)),
                ]);
            }

            $currentDate->addDay();
        }
    }
}
