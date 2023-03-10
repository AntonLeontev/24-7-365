<?php

namespace App\Providers;

use App\Events\BillingPeriodEnded;
use App\Events\CanceledUnconfirmedContractChange;
use App\Events\ContractAmountIncreased;
use App\Events\ContractCanceled;
use App\Events\ContractCreated;
use App\Events\ContractFinished;
use App\Events\ContractTerminated;
use App\Events\PaymentReceived;
use App\Events\PaymentSent;
use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Listeners\ApplyContractChanges;
use App\Listeners\CancelContract;
use App\Listeners\CheckContractStatus;
use App\Listeners\CreateAdditionalPayment;
use App\Listeners\CreateContractChange;
use App\Listeners\CreateIncomingPayment;
use App\Listeners\CreateProfitability;
use App\Listeners\DeletePendingContractChanges;
use App\Listeners\DeletePendingPayments;
use App\Listeners\IncreaseContractChangeDuration;
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
            CreateContractChange::class,
            CreateIncomingPayment::class,
        ],
        ContractCanceled::class => [
            CancelContract::class,
        ],
        ContractTerminated::class => [
            DeletePendingPayments::class,
        ],
        ContractFinished::class => [
			//
        ],
        ContractAmountIncreased::class => [
            CreateContractChange::class,
			CreateAdditionalPayment::class,
        ],
		CanceledUnconfirmedContractChange::class => [
			DeletePendingPayments::class,
			DeletePendingContractChanges::class,
		],
        PaymentReceived::class => [
			UpdateContractChange::class,
            UpdateContract::class,
			DeletePendingPayments::class,
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
