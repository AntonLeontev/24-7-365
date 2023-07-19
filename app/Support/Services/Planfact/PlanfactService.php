<?php

namespace App\Support\Services\Planfact;

use App\Contracts\AccountingSystemContract;
use App\DTOs\PurchaseAmountDTO;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use App\Support\Services\Planfact\Exceptions\PlanfactException;
use App\Support\Services\Planfact\Exceptions\PlanfactUnsuccessRequestException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

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

        try {
            $pfId = $this->createContrAgent($organization);
        } catch (Exception $e) {
            Log::channel('telegram')->error($e->getMessage(), ["Organization ID: {$organization->id}"]);
            return;
        }

        $organization->pf_id = $pfId;
        $organization->saveQuietly();
    }

    public function syncContract(Contract $contract): void
    {
        if (!empty($contract->pf_id)) {
            return;
        }

        try {
            $pfId = $this->createProject($contract);
        } catch (Exception $e) {
            Log::channel('telegram')->error($e->getMessage(), ["Contract ID: {$contract->id}"]);
            return;
        }


        $contract->pf_id = $pfId;
        $contract->saveQuietly();
    }

    public function syncPayment(Payment $payment): void
    {
        try {
            if (!empty($payment->pf_id)) {
                $this->updatePayment($payment);
                return;
            }

            if ($payment->type === PaymentType::credit) {
                $createMethod = 'createOutcome';
            } else {
                $createMethod = 'createIncome';
            }

            $date = $payment->paid_at ?? $payment->planned_at;

            $response = $this->api->{$createMethod}(
                $date->format('Y-m-d'),
                $payment->load('contract')->contract->organization->pf_id,
                $payment->contract->pf_id,
                $payment->amount->amount(),
                $payment->id,
                $payment->description,
            );

            $this->validateResponse($response);
        } catch (Exception $e) {
            Log::channel('telegram')->error($e->getMessage(), ["Payment ID: {$payment->id}"]);
            return;
        }
        

        $payment->pf_id = $response->json('data.operationId');
        $payment->saveQuietly();
    }

    public function deletePayment(Payment $payment): void
    {
        try {
            $response = $this->api->deletePayment($payment->pf_id);

            $this->validateResponse($response);
        } catch (Exception $e) {
            Log::channel('telegram')->error($e->getMessage(), ["Payment ID: {$payment->id}"]);
            return;
        }
    }

    public function getPurchasesAmount(Carbon $date): PurchaseAmountDTO
    {
        $balance = $this->api->getAccountBalance(now())->json('data.total');
        $cashflow = $this->api->getCashflow(now(), $date);

        $outPlan = $cashflow->json('data.outcomePlanValue');
        $outFact = $cashflow->json('data.outcomeFactValue');

        return new PurchaseAmountDTO($date, $balance - $outPlan + $outFact);
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
            $organization->accounts->first()->payment_account,
            $organization->id,
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

    /**
     * @throws PlanfactException
     */
    private function updatePayment(Payment $payment): void
    {
        if ($payment->type === PaymentType::debet) {
            throw new PlanfactException('Try to update incoming payment with id ' . $payment->id);
        }

        $date = $payment->paid_at ?? $payment->planned_at;
        $isCommitted = $payment->status === PaymentStatus::processed;

        $response = $this->api->updateOutcome(
            $payment->pf_id,
            $date->format('Y-m-d'),
            $payment->load('contract')->contract->organization->pf_id,
            $payment->contract->pf_id,
            $payment->amount->amount(),
            $payment->id,
            $payment->description,
            $isCommitted,
        );

        $this->validateResponse($response);
    }

    /**
     * @throws PlanfactUnsuccessRequestException
     */
    private function validateResponse(Response $response): void
    {
        if ($response->json('isSuccess')) {
            return;
        }

        throw new PlanfactUnsuccessRequestException($response);
    }
}
