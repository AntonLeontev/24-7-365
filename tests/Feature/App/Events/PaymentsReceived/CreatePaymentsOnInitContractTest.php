<?php

namespace Tests\Feature\App\Events\PaymentsReceived;


use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Events\ContractCreated;
use App\Events\ContractTariffChanging;
use App\Events\PaymentReceived;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentsOnInitContractTest extends TestCase
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
	}
	
	public function test_creating_payments_on_monthly_tariff_contract()
	{
		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'amount' => 62000000,
			'tariff_id' => Tariff::where('duration', 6)->first()->id,
			'paid_at' => now(),
		]);
	
		event(new ContractCreated($this->contract));
		$this->contract->refresh();

		$payment = $this->contract->payments->where('type', PaymentType::debet)->first();
		event(new PaymentReceived($payment));
		$this->contract->refresh();

		$this->assertDatabaseHas('contract_changes', [
			'contract_id' => $this->contract->id,
			'tariff_id' => $this->contract->tariff->id,
			'amount' => $this->contract->amount->raw(),
			'status' => ContractChangeStatus::actual,
            'starts_at' => now()->format('Y-m-d'),
		]);

		$this->assertDatabaseHas('contracts', [
			'id' => $this->contract->id,
			'status' => ContractStatus::active,
			'paid_at' => now()->format('Y-m-d H:i:s'),
		]);

		// 1 входящий платеж
		// 1 платеж за первые периоды
		// регулярных: tariffDuration - 1 - settings
		// 1 регулярный + тело

		$monthlyProfit = $this->contract->amount->raw() * $this->contract->tariff->annual_rate / 100 / 12;

		$this->assertDatabaseCount('payments', 6);

		$this->assertDatabaseHas('payments', [
			'contract_id' => $this->contract->id,
			'amount' => (int)($monthlyProfit * settings()->payments_start),
			'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start)->format('Y-m-d'),
		]);

		$this->assertDatabaseHas('payments', [
			'contract_id' => $this->contract->id,
			'amount' => (int)($monthlyProfit),
			'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start + 1)->format('Y-m-d'),
		]);

		$payments = Payment::where('amount', (int)($monthlyProfit))->get();
		$this->assertSame(3, $payments->count());

		$this->assertDatabaseHas('payments', [
			'contract_id' => $this->contract->id,
			'amount' => (int)($monthlyProfit + $this->contract->amount->raw()),
			'planned_at' => $this->contract->paid_at->addMonths(6)->format('Y-m-d'),
		]);
	}

	public function test_creating_payments_on_at_the_end_tariff_contract() {
		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'amount' => 62000000,
			'tariff_id' => Tariff::where('title', 'Platinum 1')->first()->id,
			'paid_at' => now(),
		]);
	
		event(new ContractCreated($this->contract));
		$this->contract->refresh();

		$payment = $this->contract->payments->where('type', PaymentType::debet)->first();
		event(new PaymentReceived($payment));
		$this->contract->refresh();

		$this->assertDatabaseHas('contract_changes', [
			'contract_id' => $this->contract->id,
			'tariff_id' => $this->contract->tariff->id,
			'amount' => $this->contract->amount->raw(),
			'status' => ContractChangeStatus::actual,
            'starts_at' => now()->format('Y-m-d'),
		]);

		$this->assertDatabaseHas('contracts', [
			'id' => $this->contract->id,
			'status' => ContractStatus::active,
			'paid_at' => now()->format('Y-m-d H:i:s'),
		]);

		$this->assertDatabaseCount('payments', 2);

		$monthlyProfit = $this->contract->amount->raw() * $this->contract->tariff->annual_rate / 100 / 12;

		$this->assertDatabaseHas('payments', [
			'contract_id' => $this->contract->id,
			'amount' => (int)($monthlyProfit * $this->contract->tariff->duration) + $this->contract->amount->raw(),
			'planned_at' => $this->contract->paid_at->addMonths($this->contract->tariff->duration)->format('Y-m-d'),
		]);
	}
}
