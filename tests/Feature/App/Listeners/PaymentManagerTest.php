<?php

namespace Tests\Feature\App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractChangeCanceled;
use App\Events\ContractCreated;
use App\Listeners\DebetPaymentManager;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\PaymentFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentManagerTest extends TestCase
{
	use RefreshDatabase;
	use WithFaker;


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
        $manager = new DebetPaymentManager;

		$manager->createInitialPayment(new ContractCreated($this->contract));

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
		$manager = new DebetPaymentManager;
		$amount = 1000023;

		$manager->createAdditionalPayment(
			new ContractChangingWithIncreasingAmount($this->contract, $amount, $this->contract->tariff_id)
		);

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $amount,
            'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
		]);
	}

	public function test_deleting_pending_debet_payments()
	{
		PaymentFactory::new()->count(3)->create([
			'account_id' => $this->contract->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
		]);

		$manager = new DebetPaymentManager;

		$manager->deleteDebetPendingPayments(new ContractChangeCanceled($this->contract));

		$this->assertTrue($this->contract->refresh()->payments
			->where('status', PaymentStatus::pending)
			->where('type', PaymentType::debet)
			->isEmpty()
		);
	}

	public function test_not_deleting_pending_credit_payments()
	{
		PaymentFactory::new()->count(3)->create([
			'account_id' => $this->contract->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
		]);

		$manager = new DebetPaymentManager;

		$manager->deleteDebetPendingPayments(new ContractChangeCanceled($this->contract));

		$this->assertSame(
			3, 
			$this->contract->refresh()->payments
				->where('status', PaymentStatus::pending)
				->where('type', PaymentType::credit)
				->count()
		);
	}

	public function test_not_deleting_not_pending_payments()
	{
		PaymentFactory::new()->count(10)->create([
			'account_id' => $this->contract->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'type' => $this->faker()->randomElement(PaymentType::cases()),
            'status' => $this->faker()->randomElement(PaymentStatus::cases()),
		]);

		$pendingDebetPaymentsCount = $this->contract->refresh()->payments
				->where('status', PaymentStatus::pending)
				->where('type', PaymentType::debet)
				->count();

		$manager = new DebetPaymentManager;

		$manager->deleteDebetPendingPayments(new ContractChangeCanceled($this->contract));

		$this->assertSame(
			$this->contract->refresh()->payments->count(), 
			10 - $pendingDebetPaymentsCount
		);
	}
}
