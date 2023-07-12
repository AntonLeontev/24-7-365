<?php

namespace App\Support\Services\Planfact\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class PlanfactUnsuccessRequestException extends Exception
{
    public function __construct(Response $response)
    {
        $this->message = sprintf(
            '[%s] request to [%s] was unsuccess: %s',
            $response->transferStats->getRequest()->getMethod(),
            $response->transferStats->getRequest()->getUri(),
            $response->json('errorMessage'),
        );
    }
}
