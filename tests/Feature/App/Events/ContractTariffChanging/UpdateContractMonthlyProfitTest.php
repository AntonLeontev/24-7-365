<?php

namespace Tests\Feature\App\Events\ContractTariffChanging;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
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
            'amount' => 62000000,
            'tariff_id' => Tariff::where('getting_profit', Tariff::MONTHLY)->where('duration', 6)->first()->id,
            'paid_at' => now(),
        ]);

        event(new ContractCreated($this->contract));

        $payment = $this->contract->refresh()->payments->where('type', PaymentType::debet)->first();
        event(new PaymentReceived($payment));
        $this->contract->refresh();
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

        $monthlyProfit = (int) ($this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw());
        $newMonthlyProfit = (int) ($newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw());

        // 1 входящий платеж
        // 1 плат: старая + новая доходность
        // 7 платежей новая доходность
        // платеж тело + новая доходность

        $payments = Payment::whereNull('deleted_at')->get();
        $this->assertCount(10, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $monthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(2)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) ($newMonthlyProfit + $monthlyProfit))->get();
        $this->assertCount(1, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(3)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) $newMonthlyProfit)->get();
        $this->assertCount(7, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $this->contract->amount->raw()),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + 1)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) ($newMonthlyProfit + $this->contract->amount->raw()))->get();
        $this->assertCount(1, $payments);
    }

    public function test_change_on_second_period()
    {
        $this->withoutExceptionHandling();

        $newTariff = Tariff::where('title', 'Standart')->get()->last();

        event(new BillingPeriodEnded($this->contract->refresh()));

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

        $monthlyProfit = (int) ($this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw());
        $newMonthlyProfit = (int) ($newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw());

        // 1 входящий платеж
        // 1 плат: старая + старая доходность
        // 8 платежей новая доходность
        // платеж тело + новая доходность

        $this->contract->refresh();

        $payments = Payment::whereNull('deleted_at')->get();
        $this->assertCount(11, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($monthlyProfit + $monthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(2)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) (2 * $monthlyProfit))->get();
        $this->assertCount(1, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(3)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) $newMonthlyProfit)->get();
        $this->assertCount(8, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $this->contract->amount->raw()),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + 2)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) ($newMonthlyProfit + $this->contract->amount->raw()))->get();
        $this->assertCount(1, $payments);

        //Profitability
        $this->assertCount(11, $this->contract->profitabilities);

        foreach ($this->contract->profitabilities->load('payment') as $profitability) {
            $this->assertNotNull($profitability->payment);
        }
    }

    public function test_change_on_more_than_payments_start_period()
    {
        $this->withoutExceptionHandling();

        $newTariff = Tariff::where('title', 'Standart')->get()->last();

        $periods = random_int(2, 4);
        foreach (range(1, $periods) as $value) {
            event(new BillingPeriodEnded($this->contract->refresh()));
        }

        $this->contract->refresh();

        event(new ContractTariffChanging($this->contract, $newTariff->id));
        $this->contract->refresh();

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

        $monthlyProfit = (int) ($this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw());
        $newMonthlyProfit = (int) ($newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw());

        // 1 входящий платеж
        // 1 плат: старая + старая доходность
        // 1 плат: старая доходность в конце тек периода
        // 8 платежей новая доходность
        // платеж тело + новая доходность

        $payments = Payment::whereNull('deleted_at')->get();
        $this->assertCount(10 + $periods, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($monthlyProfit + $monthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(2)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) (2 * $monthlyProfit))->get();
        $this->assertCount(1, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(2 + $periods)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) $newMonthlyProfit)->get();
        $this->assertCount(8, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $this->contract->amount->raw()),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + 1 + $periods)->format('Y-m-d'),
            'paid_at' => null,
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', (int) ($newMonthlyProfit + $this->contract->amount->raw()))->get();
        $this->assertCount(1, $payments);

        //Profitability
        $this->assertCount($periods + 1 + $newTariff->duration, $this->contract->profitabilities);

        foreach ($this->contract->profitabilities->load('payment') as $profitability) {
            $this->assertNotNull($profitability->payment);
        }
    }

    public function test_changing_to_at_the_end_profit_tariff_on_first_period()
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

        $monthlyProfit = $this->contract->tariff->annual_rate / 12 / 100 * $this->contract->amount->raw();
        $newMonthlyProfit = $newTariff->annual_rate / 12 / 100 * $this->contract->amount->raw();

        $payments = $this->contract->refresh()->payments;
        $this->assertCount(3, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $monthlyProfit,
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start)->format('Y-m-d'),
            'deleted_at' => null,
        ]);
        $payments = Payment::where('amount', $monthlyProfit * ($this->contract->duration() + 1))->get();
        $this->assertCount(1, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $newMonthlyProfit * $newTariff->duration + $this->contract->amount->raw(),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + $this->contract->duration() + 1)->format('Y-m-d'),
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', $newMonthlyProfit * $newTariff->duration + $this->contract->amount->raw())->get();
        $this->assertCount(1, $payments);
    }

    public function test_changing_to_at_the_end_profit_tariff_on_second_period()
    {
        $this->withoutExceptionHandling();

        event(new BillingPeriodEnded($this->contract->refresh()));

        $newTariff = Tariff::where('title', 'Platinum 1')->get()->last();

        event(new ContractTariffChanging($this->contract->refresh(), $newTariff->id));

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

        $payments = $this->contract->refresh()->payments;
        $this->assertCount(3, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $monthlyProfit * 2,
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start)->format('Y-m-d'),
            'deleted_at' => null,
        ]);
        $payments = Payment::where('amount', $monthlyProfit * ($this->contract->duration() + 1))->get();
        $this->assertCount(1, $payments);

        $this->assertDatabaseHas('payments', [
            'account_id' => $this->contract->organization->accounts->first()->id,
            'contract_id' => $this->contract->id,
            'amount' => $newMonthlyProfit * $newTariff->duration + $this->contract->amount->raw(),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $this->contract->paid_at->addMonths($newTariff->duration + $this->contract->duration() + 1)->format('Y-m-d'),
            'deleted_at' => null,
        ]);

        $payments = Payment::where('amount', $newMonthlyProfit * $newTariff->duration + $this->contract->amount->raw())->get();
        $this->assertCount(1, $payments);
    }
}
