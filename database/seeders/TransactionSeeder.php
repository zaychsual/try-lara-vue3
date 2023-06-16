<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'title' => 'Gajian',
                'amount' => 6000000,
                'type' => 'revenue',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Bayar Internet',
                'amount' => 315000,
                'type' => 'expense',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        Transaction::insert($datas);
    }
}
