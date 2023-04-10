<?php

namespace Tests\Feature\App\Events\ContractTariffChanging;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractTariffChanging;
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

class UpdateContractInTheEndProfitTest extends TestCase
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
		$this->user->assignRole('Клиент');
		auth()->login($this->user);
	
		$this->organization = OrganizationFactory::new()->create(['user_id' => $this->user->id]);

		AccountFactory::new()->create(['organization_id' => $this->organization->id]);

		$this->contract = ContractFactory::new()->create([
			'user_id' => $this->user->id,
			'organization_id' => $this->organization->id,
			'tariff_id' => Tariff::where('title', 'Platinum 1')->first()->id,
			'paid_at' => now(),
		]);

		$payment = Payment::create([
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $this->contract->amount,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => now()->addMonths($this->contract->tariff->duration),
			'paid_at' => null,
		]);

		ProfitabilityFactory::new()->count(random_int(1, 5))->create(['payment_id' => $payment->id]);
	}

	public function test_creating_contract_change()
	{
		$this->withoutExceptionHandling();

		$newTariff = Tariff::where('title', 'Platinum 1')->get()->last();
		
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

		$newAmount = $this->contract->amount->raw() * (1 + $newTariff->annual_rate / 100 / 12 * $newTariff->duration);

		$this->assertDatabaseHas('payments', [
			'account_id' => $this->organization->accounts->first()->id,
			'contract_id' => $this->contract->id,
			'amount' => $newAmount,
			'type' => PaymentType::credit,
			'status' => PaymentStatus::pending,
			'planned_at' => now()->addMonths($newTariff->duration)->format('Y-m-d'),
			'paid_at' => null,
			'deleted_at' => null,
		]);

		$payment = Payment::where('contract_id', $this->contract->id)->where('type', PaymentType::credit)->get()->last();

		$paymentIds = $this->contract->refresh()->profitabilities->pluck('payment_id')->unique();

		$this->assertCount(1, $paymentIds);
		$this->assertSame($payment->id, $paymentIds->first());
	}
}
