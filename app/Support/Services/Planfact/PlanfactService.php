<?php

namespace App\Support\Services\Planfact;

use App\Contracts\AccountingSystemContract;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use App\Support\Services\Planfact\Exceptions\PlanfactUnsuccessRequestException;
use Illuminate\Http\Client\Response;

class PlanfactService implements AccountingSystemContract
{
    public function __construct(public PlanfactApi $api)
    {
    }

    public function syncOrganization(Organization $organization): void
    {
        if (!empty($organization->pf_id)) {
            $this->updateContrAgent($organization);
            return;
        }

        $pfId = $this->createContrAgent($organization);

        $organization->pf_id = $pfId;
        $organization->saveQuietly();
    }

    public function syncContract(Contract $contract): void
    {
        if (!empty($contract->pf_id)) {
            return;
        }

        $pfId = $this->createProject($contract);

        $contract->pf_id = $pfId;
        $contract->saveQuietly();
    }

    public function syncPayment(Payment $payment): void
    {
    }

    private function createContrAgent(Organization $organization): int
    {
        $response = $this->api->createContrAgent(
            $organization->title,
            $organization->inn,
            $organization->kpp,
            $organization->accounts->first()->payment_account,
            $organization->id,
        );

        $this->validateResponse($response);

        return $response->json('data.contrAgentId');
    }

    private function updateContrAgent(Organization $organization): void
    {
        $response = $this->api->updateContrAgent(
            $organization->pf_id,
            $organization->title,
            $organization->inn,
            $organization->kpp,
            $organization->accounts->first()->payment_account
        );

        $this->validateResponse($response);
    }

    private function createProject(Contract $contract): int
    {
        $response = $this->api->createProject(
            "Договор №{$contract->id}",
            sprintf(
                '%s ИНН %s',
                $contract->organization->title,
				$contract->organization->inn
            ),
            $contract->id,
        );

        $this->validateResponse($response);

        return $response->json('data.projectId');
    }

    private function validateResponse(Response $response): void
    {
        if ($response->json('isSuccess')) {
            return;
        }

        throw new PlanfactUnsuccessRequestException($response);
    }
}
