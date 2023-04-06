<?php

namespace Tests\Feature\App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Events\ContractAmountIncreased;
use App\Events\ContractCreated;
use App\Listeners\ContractChangeCreator;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
use App\ValueObjects\Amount;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractChangeCreatorTest extends TestCase
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
		$creator = new ContractChangeCreator;

		$creator->createInitContractChange(new ContractCreated($this->contract));

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
		$creator = new ContractChangeCreator;
		$addititionAmount = 10000_00;

		$creator->createIncreaseAmountContractChange(new ContractAmountIncreased($this->contract, $addititionAmount));

		$this->assertDatabaseHas('contract_changes', [
			'contract_id' => $this->contract->id,
            'type' => ContractChangeType::increaseAmount,
            'tariff_id' => $this->contract->tariff->id,
            'status' => ContractChangeStatus::pending,
            'amount' => $this->contract->amount->raw() + $addititionAmount,
            'starts_at' => null,
		]);
    }
}
