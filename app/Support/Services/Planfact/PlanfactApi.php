<?php

namespace App\Support\Services\Planfact;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PlanfactApi
{
    public static function getCompanies(): Response
    {
        return Http::planfact()
            ->get('/api/v1/companies');
    }

    public static function getAccounts(): Response
    {
        return Http::planfact()
            ->get('/api/v1/accounts');
    }

    public static function getOperations(): Response
    {
        return Http::planfact()
            ->get('/api/v1/operations');
    }

    public static function getOperationCategories(): Response
    {
        return Http::planfact()
            ->get('/api/v1/operationcategories', [
                'filter.operationCategoryType' => 'Liabilities'
            ]);
    }

    public static function getContrAgents(): Response
    {
        return Http::planfact()
            ->get('/api/v1/contragents');
    }

    public static function createContrAgent(
        string $title,
        ?string $inn = null,
        ?string $kpp = null,
        ?string $account = null,
        ?string $externalId = null,
    ): Response {
        return Http::planfact()
            ->post('/api/v1/contragents', [
                'title' => $title,
                'longTitle' => $title,
                'contrAgentInn' => $inn,
                'contrAgentKpp' => $kpp,
                'contrAgentAcct' => $account,
                'contrAgentType' => 'Mixed',
                'externalId' => $externalId,
                'contrAgentGroupId' => config('services.planfact.contragent_group'),
                'rememberCategory' => true,
                'operationIncomeCategoryId' => config('services.planfact.income_category'),
                'operationOutcomeCategoryId' => config('services.planfact.outcome_profit_category'),
            ]);
    }

    public static function updateContrAgent(
        int $id,
        ?string $title = null,
        ?string $inn = null,
        ?string $kpp = null,
        ?string $account = null,
        ?string $externalId = null,
    ): Response {
        return Http::planfact()
            ->put("/api/v1/contragents/{$id}", [
                'title' => $title,
                'longTitle' => $title,
                'contrAgentInn' => $inn,
                'contrAgentKpp' => $kpp,
                'contrAgentAcct' => $account,
                'externalId' => $externalId,
                'contrAgentType' => 'Mixed',
                'contrAgentGroupId' => config('services.planfact.contragent_group'),
                'rememberCategory' => true,
                'operationIncomeCategoryId' => config('services.planfact.income_category'),
                'operationOutcomeCategoryId' => config('services.planfact.outcome_profit_category'),
            ]);
    }

    public static function getContrAgent(int $id): Response
    {
        return Http::planfact()
            ->get("/api/v1/contragents/{$id}");
    }

    public static function createProject(
        string $title,
        ?string $description = null,
        ?string $externalID = null,
    ): Response {
        return Http::planfact()
            ->post('/api/v1/projects', [
                'title' => $title,
                'description' => $description,
                'externalId' => $externalID,
                'closed' => false,
            ]);
    }

    public static function createIncome(
        string $date,
        int $contrAgentId,
        int $projectId,
        float $value,
        string $externalId,
        ?string $comment = null,
    ): Response {
        return Http::planfact()
            ->post('/api/v1/operations/income', [
                'operationDate' => $date,
                'contrAgentId' => $contrAgentId,
                'accountId' => config('services.planfact.account_id'),
                'comment' => $comment,
                'isCommitted' => true,
                'items' => [
                    [
                        'calculationDate' => $date,
                        'isCalculationCommitted' => true,
                        'operationCategoryId' => config('services.planfact.income_category'),
                        'contrAgentId' => $contrAgentId,
                        'projectId' => $projectId,
                        'value' => $value,
                    ],
                ],
                'externalId' => $externalId,
            ]);
    }

    public static function createOutcome(
        string $date,
        int $contrAgentId,
        int $projectId,
        float $value,
        string $externalId,
        ?string $comment = null,
    ): Response {
        return Http::planfact()
            ->post('/api/v1/operations/outcome', [
                'operationDate' => $date,
                'contrAgentId' => $contrAgentId,
                'accountId' => config('services.planfact.account_id'),
                'comment' => $comment,
                'isCommitted' => false,
                'items' => [
                    [
                        'calculationDate' => $date,
                        'operationCategoryId' => config('services.planfact.outcome_profit_category'),
                        'contrAgentId' => $contrAgentId,
                        'projectId' => $projectId,
                        'value' => $value,
                    ],
                ],
                'externalId' => $externalId,
            ]);
    }

    public static function updateOutcome(
        int $id,
        string $date,
        int $contrAgentId,
        int $projectId,
        float $value,
        string $externalId,
        ?string $comment = null,
        bool $isCommitted = false,
    ): Response {
        return Http::planfact()
            ->put("/api/v1/operations/outcome/{$id}", [
                'operationDate' => $date,
                'contrAgentId' => $contrAgentId,
                'accountId' => config('services.planfact.account_id'),
                'comment' => $comment,
                'isCommitted' => $isCommitted,
                'items' => [
                    [
                        'calculationDate' => $date,
                        'operationCategoryId' => config('services.planfact.outcome_profit_category'),
                        'contrAgentId' => $contrAgentId,
                        'projectId' => $projectId,
                        'value' => $value,
                    ],
                ],
                'externalId' => $externalId,
            ]);
    }

    public static function deletePayment(int $id): Response
    {
        return Http::planfact()
            ->delete("/api/v1/operations/{$id}");
    }

    public static function getAccountBalance(Carbon $date): Response
    {
        return Http::planfact()
            ->get("/api/v1/businessmetrics/accountbalance", [
                'filter.currentDate' => $date->format('Y-m-d'),
                'filter.accountIds' => config('services.planfact.account_id'),
            ]);
    }

    public static function getCashflow(Carbon $dateStart, Carbon $dateEnd): Response
    {
        return Http::planfact()
            ->get("/api/v1/businessmetrics/cashflow", [
                'filter.periodStartDate' => $dateStart->format('Y-m-d'),
                'filter.periodEndDate' => $dateEnd->format('Y-m-d'),
                'filter.accountId' => config('services.planfact.account_id'),
                'filter.isCalculation' => 'false',
            ]);
    }
}
