<?php

namespace Tests\Feature\App\Models;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\ContractChangeFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Organization $organization;

    public Contract $contract;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolesPermissionsSeeder']);
        $this->artisan('db:seed', ['--class' => 'TariffsSeeder']);

        $this->user = UserFactory::new()->create();

        $this->organization = OrganizationFactory::new()->create(['user_id' => $this->user->id]);

        $this->contract = ContractFactory::new()->create([
            'user_id' => $this->user->id,
            'organization_id' => $this->organization->id,
            'tariff_id' => Tariff::first(), //Standart - 3
        ]);

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $this->contract->tariff_id,
            'amount' => $this->contract->amount,
            'status' => ContractChangeStatus::past,
            'starts_at' => '1990-12-10',
        ]);
    }

    public function test_current_tariff()
    {
        $start = $this->contract->currentTariffStart();

        $this->assertSame('1990-12-10', $start->format('Y-m-d'));

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $this->contract->tariff_id,
            'type' => ContractChangeType::change,
            'status' => ContractChangeStatus::past,
            'starts_at' => '1991-12-10',
        ]);

        $start = $this->contract->refresh()->currentTariffStart();

        $this->assertSame('1990-12-10', $start->format('Y-m-d'));

        $tariffId = Tariff::where('title', 'Standart')->where('duration', 9)->first(); //Standart - 9

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $tariffId,
            'type' => ContractChangeType::change,
            'status' => ContractChangeStatus::actual,
            'starts_at' => '1992-12-10',
        ]);

        $start = $this->contract->refresh()->currentTariffStart();

        $this->assertSame('1992-12-10', $start->format('Y-m-d'));

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $tariffId,
            'type' => ContractChangeType::prolongation,
            'status' => ContractChangeStatus::actual,
            'starts_at' => '1993-12-10',
        ]);

        $start = $this->contract->refresh()->currentTariffStart();

        $this->assertSame('1993-12-10', $start->format('Y-m-d'));

        $tariffId = Tariff::where('title', 'Platinum 1')->where('duration', 12)->first(); // Platinum1 - 12

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $tariffId,
            'type' => ContractChangeType::change,
            'status' => ContractChangeStatus::actual,
            'starts_at' => '1994-12-10',
        ]);

        $start = $this->contract->refresh()->currentTariffStart();

        $this->assertSame('1994-12-10', $start->format('Y-m-d'));

        $tariffId = Tariff::where('title', 'Platinum 1')->where('duration', 24)->first(); // Platinum1 - 24

        ContractChangeFactory::new()->create([
            'contract_id' => $this->contract->id,
            'tariff_id' => $tariffId,
            'type' => ContractChangeType::change,
            'status' => ContractChangeStatus::actual,
            'starts_at' => '1995-12-10',
        ]);

        $start = $this->contract->refresh()->currentTariffStart();

        $this->assertSame('1994-12-10', $start->format('Y-m-d'));
    }
}
