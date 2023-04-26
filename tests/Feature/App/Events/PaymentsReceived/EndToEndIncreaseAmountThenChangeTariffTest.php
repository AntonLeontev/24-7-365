<?php

namespace Tests\Feature\App\Events\PaymentsReceived;

use App\Enums\ContractStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Events\PaymentReceived;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndIncreaseAmountThenChangeTariffTest extends TestCase
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

		$tariff = Tariff::where('getting_profit', Tariff::AT_THE_END)->first();

		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'amount' => 65000000,
			'tariff_id' => $tariff->id,
			'status' => ContractStatus::init,
		]);

		event(new ContractCreated($this->contract));

		$payment = $this->contract->refresh()->payments->first();
		event(new PaymentReceived($payment));
		event(new BillingPeriodEnded($this->contract));

		// Увеличение суммы
		event(new ContractChangingWithIncreasingAmount($this->contract->refresh(), 10000000, $this->contract->tariff_id));
		$payment->contract->refresh()->payments->where('type', PaymentType::debet)->last();
		event(new PaymentReceived($payment));
		event(new BillingPeriodEnded($this->contract->refresh()));

		//Смена тарифа
		$newTariff = Tariff::where('title', 'Platinum 1')->where('duration', 24)->first();
		event(new ContractChangingWithIncreasingAmount($this->contract->refresh(), 10000000, $newTariff->id));
		$payment->contract->refresh()->payments->where('type', PaymentType::debet)->last();
		event(new PaymentReceived($payment));

		$this->contract->refresh();
		// 2 периода на 650 ставка 9.58 (Ставка считается по новому тарифу), 
		// 1 период на 750 ставка 9.58, переход на 24 мес 850 ставка 9.58
	}

	public function test_contract_changes()
	{
		$changes = $this->contract->contractChanges;
		$this->assertCount(3, $changes);
	}

	public function test_profitabilities()
	{
		$profitabilities = $this->contract->profitabilities;
		$this->assertCount(24, $profitabilities);

		$payments = $this->contract->payments->where('type', PaymentType::credit);
		$this->assertCount(1, $payments);
		
		$payment = $this->contract->payments->where('type', PaymentType::credit)->last();

		$profitabilities = $this->contract->profitabilities
			->filter(function($profitability) use ($payment) {
				if ($payment->id !== $profitability->payment_id) {
					return false;
				}

				return $profitability->amount->raw() === (int) (650_000_00 * 1.15/12);
			});
		
		$this->assertCount(2, $profitabilities);

		$this->assertDatabaseHas('profitabilities', [
			'amount' => (int) (650_000_00 * 1.15/12),
			'accrued_at' => $this->contract->paid_at->addMonths(2)->format('Y-m-d'),
			'payment_id' => $payment->id,
			'contract_id' => $this->contract->id
		]);

		$this->assertDatabaseHas('profitabilities', [
			'amount' => (int) (650_000_00 * 1.15/12),
			'accrued_at' => $this->contract->paid_at->addMonths(1)->format('Y-m-d'),
			'payment_id' => $payment->id,
			'contract_id' => $this->contract->id
		]);

		$profitabilities = $this->contract->profitabilities
			->filter(function($profitability) use ($payment) {
				if ($payment->id !== $profitability->payment_id) {
					return false;
				}

				return $profitability->amount->raw() === (int) (750_000_00 * 1.15/12);
			});
		
		$this->assertCount(1, $profitabilities);

		$this->assertDatabaseHas('profitabilities', [
			'amount' => (int) (750_000_00 * 1.15/12),
			'accrued_at' => $this->contract->paid_at->addMonths(3)->format('Y-m-d'),
			'payment_id' => $payment->id,
			'contract_id' => $this->contract->id
		]);

		$profitabilities = $this->contract->profitabilities
			->filter(function($profitability) use ($payment) {
				if ($payment->id !== $profitability->payment_id) {
					return false;
				}

				return $profitability->amount->raw() === (int) (850_000_00 * 1.15/12);
			});
		
		$this->assertCount(21, $profitabilities);

		$this->assertDatabaseHas('profitabilities', [
			'amount' => (int) (850_000_00 * 1.15/12),
			'accrued_at' => $this->contract->paid_at->addMonths(4)->format('Y-m-d'),
			'payment_id' => $payment->id,
			'contract_id' => $this->contract->id
		]);
	}

	public function test_payments()
	{
		$this->assertDatabaseHas('payments', [
			'amount' => (int) (85000000 + 650_000_00 * 1.15/12 * 2 + 750_000_00 * 1.15/12 + 850_000_00 * 1.15/12 * 21),
			'type' => 'credit',
			'planned_at' => $this->contract->paid_at->addMonths(24)->format('Y-m-d'),
		]);
	}
}
