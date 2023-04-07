<?php

namespace Tests\Feature\App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractAmountIncreasing;
use App\Events\ContractCreated;
use App\Listeners\PaymentCreator;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentCreatorTest extends TestCase
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

		AccountFactory::new()->create(['organization_id' => $this->organization->id]);

		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'tariff_id' => Tariff::inRandomOrder()->first()->id,
		]);
	}
	
	public function test_creating_initial_payment()
    {
        $creator = new PaymentCreator;

		$creator->createInitialPayment(new ContractCreated($this->contract));

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $this->contract->amount->raw(),
            'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
		]);
    }

	public function test_createing_additional_payment()
	{
		$creator = new PaymentCreator;
		$amount = 1000023;

		$creator->createAdditionalPayment(new ContractAmountIncreasing($this->contract, $amount));

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $amount,
            'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
		]);
	}
}
