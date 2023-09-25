<?php

namespace Tests\Feature\App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Events\ContractChangeCanceled;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Listeners\ContractChangeManager;
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

class ContractChangeManagerTest extends TestCase
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
            'tariff_id' => Tariff::inRandomOrder()->first()->id,
        ]);
    }

    public function test_creating_init_contract_change()
    {
        $manager = new ContractChangeManager;

        $manager->createInitContractChange(new ContractCreated($this->contract));

        $this->assertDatabaseHas('contract_changes', [
            'contract_id' => $this->contract->id,
            'type' => ContractChangeType::init,
            'tariff_id' => $this->contract->tariff->id,
            'status' => ContractChangeStatus::pending,
            'amount' => $this->contract->amount->raw(),
            'starts_at' => null,
        ]);
    }

    public function test_creating_increase_amount_contract_change()
    {
        $manager = new ContractChangeManager;
        $addititionAmount = 10000_00;
        $newTariffId = Tariff::inRandomOrder()->first()->id;

        $manager->createIncreaseAmountContractChange(
            new ContractChangingWithIncreasingAmount($this->contract, $addititionAmount, $newTariffId)
        );

        $this->assertDatabaseHas('contract_changes', [
            'contract_id' => $this->contract->id,
            'type' => ContractChangeType::change,
            'tariff_id' => $newTariffId,
            'status' => ContractChangeStatus::pending,
            'amount' => $this->contract->amount->raw() + $addititionAmount,
            'starts_at' => null,
        ]);
    }

    public function test_deleting_pending_contract_changes()
    {
        ContractChangeFactory::new()->count(2)->create([
            'contract_id' => $this->contract->id,
            'type' => ContractChangeType::change,
            'tariff_id' => $this->contract->tariff_id,
            'status' => ContractChangeStatus::pending,
            'amount' => $this->contract->amount,
            'starts_at' => null,
        ]);

        $manager = new ContractChangeManager;

        $manager->deletePendingContractChanges(new ContractChangeCanceled($this->contract));

        $this->assertTrue($this->contract->refresh()->contractChanges->where('status', ContractChangeStatus::pending)->isEmpty());
    }
}
