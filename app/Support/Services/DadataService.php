<?php

namespace App\Support\Services;

use App\Contracts\SuggestionsContract;
use MoveMoveIo\DaData\Enums\BankStatus;
use MoveMoveIo\DaData\Enums\BankType;
use MoveMoveIo\DaData\Enums\CompanyStatus;
use MoveMoveIo\DaData\Enums\CompanyType;
use MoveMoveIo\DaData\Facades\DaDataBank;
use MoveMoveIo\DaData\Facades\DaDataCompany;

class DadataService implements SuggestionsContract
{
    public function company(string $query): array
    {
        $result =  DaDataCompany::prompt(
            $query,
            20,
            [CompanyStatus::ACTIVE],
            CompanyType::LEGAL
        );

        if (empty($result['suggestions'])) {
            $result = DaDataCompany::prompt(
                $query,
                20,
                [CompanyStatus::ACTIVE],
                CompanyType::INDIVIDUAL
            );
        }

		return $result;
    }

    public function bank(string $query): array
    {
        return DaDataBank::prompt(
            $query,
            20,
            [BankStatus::ACTIVE],
            [BankType::BANK, BankType::BANK_BRANCH]
        );
    }
}
