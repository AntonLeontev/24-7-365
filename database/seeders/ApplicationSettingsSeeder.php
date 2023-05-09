<?php

namespace Database\Seeders;

use App\Models\ApplicationSettings;
use Illuminate\Database\Seeder;

class ApplicationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicationSettings::create([
            'payments_start' => 2,
            'organization_title' => 'ООО "ЭСЭМПИЭЛЬ"',
            'inn' => '7724305105',
            'kpp' => '770401001',
            'ogrn' => '1157746098872',
            'director' => 'Лозовой О. И.',
            'director_genitive' => 'Лозового О. И.',
            'accountant' => 'Лозовой О. И.',
            'legal_address' => '119002, Москва г, Арбат ул, дом № 44, строение 3, к 66',
            'actual_address' => '119002, Москва г, Арбат ул, дом № 44, строение 3, к 66',
            'payment_account' => '40701810600000003217',
            'correspondent_account' => '30101810400000000225',
            'bik' => '044525225',
            'bank' => 'ПАО Сбербанк',
            'email' => 'test@example.ru',
            'phone' => '+7-912-000-00-00',
        ]);
    }
}
