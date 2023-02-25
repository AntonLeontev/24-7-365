<?php

namespace Database\Seeders;

use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tariffs = [
            //------------------------ Standart ---------------------------
            [
                'title' => 'Standart',
                'annual_rate' => 25,
                'duration' => 3,
                'min_amount' => 500_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Standart',
                'annual_rate' => 30,
                'duration' => 6,
                'min_amount' => 500_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Standart',
                'annual_rate' => 35,
                'duration' => 9,
                'min_amount' => 500_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],

            //------------------------ Gold 1 ---------------------------

            [
                'title' => 'Gold 1',
                'annual_rate' => 40,
                'duration' => 12,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Gold 1',
                'annual_rate' => 50,
                'duration' => 24,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Gold 1',
                'annual_rate' => 60,
                'duration' => 36,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],

            //------------------------ Gold 2 ---------------------------

            [
                'title' => 'Gold 2',
                'annual_rate' => 70,
                'duration' => 12,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Gold 2',
                'annual_rate' => 80,
                'duration' => 24,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Gold 2',
                'annual_rate' => 90,
                'duration' => 36,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::MONTHLY,
                'getting_deposit' => Tariff::AT_THE_END
            ],

            //------------------------ Platinum 1 ---------------------------

            [
                'title' => 'Platinum 1',
                'annual_rate' => 100,
                'duration' => 12,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Platinum 1',
                'annual_rate' => 115,
                'duration' => 24,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Platinum 1',
                'annual_rate' => 125,
                'duration' => 36,
                'min_amount' => 500_000_00,
                'max_amount' => 5_000_000_00,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],

            //------------------------ Platinum 2 ---------------------------

            [
                'title' => 'Platinum 2',
                'annual_rate' => 150,
                'duration' => 12,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Platinum 2',
                'annual_rate' => 175,
                'duration' => 24,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],
            [
                'title' => 'Platinum 2',
                'annual_rate' => 200,
                'duration' => 36,
                'min_amount' => 5_000_000_00,
                'max_amount' => 0,
                'getting_profit' => Tariff::AT_THE_END,
                'getting_deposit' => Tariff::AT_THE_END
            ],
        ];

        foreach ($tariffs as $tariff) {
            $this->create($tariff);
        }
    }

    private function create(array $tariff)
    {
        Tariff::create([
            'title' => $tariff['title'],
            'annual_rate' => $tariff['annual_rate'],
            'duration' => $tariff['duration'],
            'min_amount' => $tariff['min_amount'],
            'max_amount' => $tariff['max_amount'],
            'getting_profit' => $tariff['getting_profit'],
            'getting_deposit' => $tariff['getting_deposit'],
        ]);
    }
}
