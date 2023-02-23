<?php

namespace App\Providers;

use App\Events\ContractCanceled;
use App\Events\ContractCreated;
use App\Events\ContractTerminated;
use App\Events\PaymentReceived;
use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Listeners\ActivateContract;
use App\Listeners\CancelContract;
use App\Listeners\CreateIncomingPayment;
use App\Listeners\DeletePendingPayments;
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
			CreateIncomingPayment::class,
		],
		ContractCanceled::class => [
			CancelContract::class,
		],
		ContractTerminated::class => [
			DeletePendingPayments::class,
		],
		PaymentReceived::class => [
			ActivateContract::class,
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
