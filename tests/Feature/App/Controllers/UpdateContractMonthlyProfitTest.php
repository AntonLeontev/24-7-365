<?php

namespace Tests\Feature\App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Events\ContractCreated;
use App\Events\ContractTariffChanging;
use App\Events\PaymentReceived;
use App\Http\Controllers\ContractController;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\ProfitabilityFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateContractMonthlyProfitTest extends TestCase
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
        $this->artisan('db:seed', ['--class' => 'ApplicationSettingsSeeder']);

		$this->user = UserFactory::new()->create();
		$this->user->assignRole('Клиент');
		auth()->login($this->user);
	
		$this->organization = OrganizationFactory::new()->create(['user_id' => $this->user->id]);

		AccountFactory::new()->create(['organization_id' => $this->organization->id]);

		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'tariff_id' => Tariff::where('getting_profit', Tariff::MONTHLY)->first()->id,
			'paid_at' => now(),
		]);

		event(new ContractCreated($this->contract));

		$payment = $this->contract->payments->where('type', PaymentType::debet)->first();
		event(new PaymentReceived($payment));
	}

	public function test_change_on_first_period()
	{
		$this->withoutExceptionHandling();

		$newTariff = Tariff::where('title', 'Standart')->get()->last();
		
		event(new ContractTariffChanging($this->contract, $newTariff->id));

		$this->assertDatabaseHas('contract_changes', [
			'contract_id' => $this->contract->id,
			'type' => ContractChangeType::change,
			'tariff_id' => $newTariff->id,
			'status' => ContractChangeStatus::waitingPeriodEnd,
			'amount' => $this->contract->amount->raw(),
			'starts_at' => null,
			'duration' => 0,
			'deleted_at' => null,
		]);

		$monthlyProfit = $this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw();
		$newMonthlyProfit = $newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw();

		// 1 плат: старая + новая доходность 
		// 7 платежей новая доходность 
		// платеж тело + новая доходность

		$this->assertDatabaseCount('payments', 10);

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newMonthlyProfit + $monthlyProfit,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths(2),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', $newMonthlyProfit + $monthlyProfit)->get();
		$this->assertCount(1, $payments);


		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newMonthlyProfit,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths(3),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', $newMonthlyProfit)->get();
		$this->assertCount(7, $payments);


		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newMonthlyProfit + $this->contract->amount->raw(),
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + 1),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', $newMonthlyProfit + $this->contract->amount->raw())->get();
		$this->assertCount(1, $payments);
	}

	public function test_change_on_second_period()
	{
		$this->withoutExceptionHandling();

		$newTariff = Tariff::where('title', 'Standart')->get()->last();

		event(new BillingPeriodEnded($this->contract));

		event(new ContractTariffChanging($this->contract, $newTariff->id));
		
		$this->assertDatabaseHas('contract_changes', [
			'contract_id' => $this->contract->id,
			'type' => ContractChangeType::change,
			'tariff_id' => $newTariff->id,
			'status' => ContractChangeStatus::waitingPeriodEnd,
			'amount' => $this->contract->amount->raw(),
			'starts_at' => null,
			'duration' => 0,
			'deleted_at' => null,
		]);

		$monthlyProfit = $this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw();
		$newMonthlyProfit = $newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw();

		// 1 входящий платеж 
		// 1 плат: старая + старая доходность 
		// 8 платежей новая доходность 
		// платеж тело + новая доходность

		$this->assertDatabaseCount('payments', 11);

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $monthlyProfit * 2,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths(2),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', 2 * $monthlyProfit)->get();
		$this->assertCount(1, $payments);


		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newMonthlyProfit,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths(3),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', $newMonthlyProfit)->get();
		$this->assertCount(8, $payments);


		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newMonthlyProfit + $this->contract->amount->raw(),
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + 1),
			'paid_at' => null,
		]);

		$payments = Payment::where('amount', $newMonthlyProfit + $this->contract->amount->raw())->get();
		$this->assertCount(1, $payments);
	}
}
