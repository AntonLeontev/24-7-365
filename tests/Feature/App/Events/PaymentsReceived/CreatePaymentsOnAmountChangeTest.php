<?php

namespace Tests\Feature\App\Events\PaymentsReceived;

use App\Enums\ContractChangeStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Events\PaymentReceived;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\User;
use App\ValueObjects\Amount;
use Database\Factories\AccountFactory;
use Database\Factories\ContractFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentsOnAmountChangeTest extends TestCase
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

    public function test_changing_monthly_to_monthly_tariff_contract()
    {
        $this->contract = ContractFactory::new()->create([
            'user_id' => $this->user->id,
            'organization_id' => $this->organization->id,
            'amount' => 62000000,
            'tariff_id' => Tariff::where('duration', 6)->first()->id,
            'paid_at' => now(),
        ]);

        $addAmount = new Amount(100_000_00);

        $this->prepareDB($addAmount, 2);

        $this->assertDatabaseHas('contract_changes', [
            'contract_id' => $this->contract->id,
            'tariff_id' => $this->contract->tariff->id,
            'amount' => $addAmount->raw() + $this->contract->amount->raw(),
            'status' => ContractChangeStatus::waitingPeriodEnd,
            'starts_at' => null,
            'duration' => 0,
        ]);

        // 2 входящиx платежa
        // 1 платеж за первые 2 периодa
        // регулярных: 3
        // 1 регулярный + тело

        $oldMonthlyProfit = $this->contract->amount->raw() * $this->contract->tariff->annual_rate / 100 / 12;
        $newMonthlyProfit = ($this->contract->amount->raw() + $addAmount->raw()) * $this->contract->tariff->annual_rate / 100 / 12;

        $payments = $this->contract->payments;
        $this->assertSame(7, $payments->count());

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($oldMonthlyProfit * 2),
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start)->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit),
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start + 2)->format('Y-m-d'),
        ]);
        $payments = Payment::where('amount', (int) ($newMonthlyProfit))->get();
        $this->assertSame(2, $payments->count());

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $this->contract->amount->raw() + $addAmount->raw()),
            'planned_at' => $this->contract->paid_at->addMonths(6)->format('Y-m-d'),
        ]);

        foreach ($this->contract->profitabilities as $profitability) {
            $this->assertNotNull($profitability->payment);
        }
    }

    public function test_changing_monthly_to_monthly_tariff_contract_first_period()
    {
        $this->contract = ContractFactory::new()->create([
            'user_id' => $this->user->id,
            'organization_id' => $this->organization->id,
            'amount' => 62000000,
            'tariff_id' => Tariff::where('duration', 6)->first()->id,
            'paid_at' => now(),
        ]);

        $addAmount = new Amount(100_000_00);

        $this->prepareDB($addAmount, 0);

        $this->assertDatabaseHas('contract_changes', [
            'contract_id' => $this->contract->id,
            'tariff_id' => $this->contract->tariff->id,
            'amount' => $addAmount->raw() + $this->contract->amount->raw(),
            'status' => ContractChangeStatus::waitingPeriodEnd,
            'starts_at' => null,
            'duration' => 0,
        ]);

        // 2 входящиx платежa
        // 1 платеж за первые 2 периодa
        // регулярных: 3
        // 1 регулярный + тело

        $oldMonthlyProfit = $this->contract->amount->raw() * $this->contract->tariff->annual_rate / 100 / 12;
        $newMonthlyProfit = ($this->contract->amount->raw() + $addAmount->raw()) * $this->contract->tariff->annual_rate / 100 / 12;

        $payments = $this->contract->payments;
        $this->assertSame(7, $payments->count());

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($oldMonthlyProfit + $newMonthlyProfit),
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start)->format('Y-m-d'),
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit),
            'planned_at' => $this->contract->paid_at->addMonths(settings()->payments_start + 1)->format('Y-m-d'),
            'deleted_at' => null,
        ]);
        $payments = Payment::where('amount', (int) ($newMonthlyProfit))->get();
        $this->assertSame(3, $payments->count());

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) ($newMonthlyProfit + $this->contract->amount->raw() + $addAmount->raw()),
            'planned_at' => $this->contract->paid_at->addMonths(6)->format('Y-m-d'),
            'deleted_at' => null,
        ]);

        foreach ($this->contract->profitabilities as $profitability) {
            $this->assertNotNull($profitability->payment);
        }
    }

    public function test_changing_end_to_end_tariff_contract()
    {
        $this->contract = ContractFactory::new()->create([
            'user_id' => $this->user->id,
            'organization_id' => $this->organization->id,
            'amount' => 62000000,
            'tariff_id' => Tariff::where('title', 'Platinum 1')->first()->id,
            'paid_at' => now(),
        ]);

        $addAmount = new Amount(100_000_00);

        $this->prepareDB($addAmount, 2);

        $this->assertDatabaseHas('contract_changes', [
            'contract_id' => $this->contract->id,
            'tariff_id' => $this->contract->tariff->id,
            'amount' => $addAmount->raw() + $this->contract->amount->raw(),
            'status' => ContractChangeStatus::waitingPeriodEnd,
            'starts_at' => null,
            'duration' => 0,
        ]);

        // 2 входящиx платежa
        // 1 доходность + тело

        $oldMonthlyProfit = $this->contract->amount->raw() * $this->contract->tariff->annual_rate / 100 / 12;
        $newMonthlyProfit = ($this->contract->amount->raw() + $addAmount->raw()) * $this->contract->tariff->annual_rate / 100 / 12;

        $payments = $this->contract->payments;
        $this->assertSame(3, $payments->count());

        $newBody = $this->contract->amount->raw() + $addAmount->raw();
        $finalAmount = $newBody + $oldMonthlyProfit * 3 + $newMonthlyProfit * ($this->contract->tariff->duration - 3);

        $this->assertDatabaseHas('payments', [
            'contract_id' => $this->contract->id,
            'amount' => (int) $finalAmount,
            'planned_at' => $this->contract->paid_at->addMonths(12)->format('Y-m-d'),
            'deleted_at' => null,
        ]);

        foreach ($this->contract->profitabilities as $profitability) {
            $this->assertNotNull($profitability->payment);
        }
    }

    private function prepareDB(Amount $addAmount, int $periods): void
    {
        event(new ContractCreated($this->contract));
        $this->contract->refresh();

        // Инициируем контракт входящим платежом
        $payment = $this->contract->payments->where('type', PaymentType::debet)->first();
        event(new PaymentReceived($payment));
        $this->contract->refresh();

        // Один период прошел
        if ($periods > 0) {
            foreach (range(1, $periods) as $period) {
                event(new BillingPeriodEnded($this->contract));
            }
        }

        // Смена суммы
        event(new ContractChangingWithIncreasingAmount($this->contract->refresh(), $addAmount->raw(), $this->contract->tariff->id));
        $this->contract->refresh();

        $incomePayment = $this->contract->payments->where('type', PaymentType::debet)->last();

        // Инициируем смену тарифа входящим платежом
        event(new PaymentReceived($incomePayment));
        $this->contract->refresh();
    }
}
