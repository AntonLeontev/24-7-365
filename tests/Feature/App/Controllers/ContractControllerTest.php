<?php

namespace Tests\Feature\App\Controllers;

use App\Http\Controllers\ContractController;
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
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContractControllerTest extends TestCase
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
        $this->user->assignRole('Клиент');
        auth()->login($this->user);

        $this->organization = OrganizationFactory::new()->create(['user_id' => $this->user->id]);

        AccountFactory::new()->create(['organization_id' => $this->organization->id]);

        $this->contract = ContractFactory::new()->create([
            'user_id' => $this->user->id,
            'organization_id' => $this->organization->id,
            'tariff_id' => Tariff::inRandomOrder()->first()->id,
        ]);
    }

    public function test_storing_contract()
    {
        $this->withoutExceptionHandling();
        Notification::fake();

        $tariffId = Tariff::where('title', 'Standart')->first()->id;
        $amount = $this->faker->numberBetween(500000, 5000000);

        $data = [
            'tariff_id' => $tariffId,
            'amount' => $amount,
        ];

        $response = $this->post(action([ContractController::class, 'store']), $data);

        $this->assertDatabaseHas('contracts', [
            'amount' => $amount * 100,
            'tariff_id' => $tariffId,
        ]);

        $response->assertOk();

        $response->assertJsonStructure(['ok', 'paymentId']);
    }
}
