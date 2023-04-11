<?php

namespace App\Providers;

use App\Events\BillingPeriodEnded;
use App\Events\ContractCanceled;
use App\Events\ContractChangeCanceled;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Events\ContractFinished;
use App\Events\ContractTariffChanging;
use App\Events\ContractTerminated;
use App\Events\PaymentReceived;
use App\Events\PaymentSent;
use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Listeners\ApplyContractChanges;
use App\Listeners\CancelContract;
use App\Listeners\CheckContractStatus;
use App\Listeners\ContractChangeManager;
use App\Listeners\CreateProfitability;
use App\Listeners\GenerateCreditPayments;
use App\Listeners\DeletePendingCreditPayments;
use App\Listeners\IncreaseContractChangeDuration;
use App\Listeners\DebetPaymentManager;
use App\Listeners\SchedulePayments;
use App\Listeners\UpdateContract;
use App\Listeners\UpdateContractChange;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Yandex\YandexExtendSocialite::class . '@handle',
            \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class . '@handle',
            \SocialiteProviders\Google\GoogleExtendSocialite::class . '@handle',
        ],
        UserBlocked::class => [],
        UserUnblocked::class => [],
        ContractCreated::class => [
            [ContractChangeManager::class, 'createInitContractChange'],
            [DebetPaymentManager::class, 'createInitialPayment'],
        ],
        ContractCanceled::class => [
            CancelContract::class,
        ],
        ContractTerminated::class => [
            DeletePendingCreditPayments::class,
        ],
        ContractFinished::class => [
        ],
        ContractTariffChanging::class => [
            [ContractChangeManager::class, 'createNewTariffContractChange'],
            //TODO Исходящие выплаты
			[GenerateCreditPayments::class, 'handle'],
        ],
        ContractChangingWithIncreasingAmount::class => [
            [ContractChangeManager::class, 'createIncreaseAmountContractChange'],
            [DebetPaymentManager::class, 'createAdditionalPayment'],
        ],
        ContractChangeCanceled::class => [
            // DeletePendingPayments::class,
            [ContractChangeManager::class, 'deletePendingContractChanges'],
            [DebetPaymentManager::class, 'deleteDebetPendingPayments'],
        ],
        PaymentReceived::class => [
            UpdateContractChange::class,
            UpdateContract::class,
            DeletePendingCreditPayments::class,
            SchedulePayments::class, //TODO
        ],
        PaymentSent::class => [
            CheckContractStatus::class,
        ],
        BillingPeriodEnded::class => [
            IncreaseContractChangeDuration::class,
            CreateProfitability::class,
            ApplyContractChanges::class,
        ],
    ];

    protected $subscribe = [];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
