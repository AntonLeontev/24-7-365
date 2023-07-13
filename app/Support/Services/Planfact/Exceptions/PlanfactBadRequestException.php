<?php

namespace App\Support\Services\Planfact\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class PlanfactBadRequestException extends Exception
{
    public function __construct(Response $response)
    {
        $this->message = sprintf(
            '[%s] request to [%s] finished with status %s %s: %s',
            $response->transferStats->getRequest()->getMethod(),
            $response->transferStats->getRequest()->getUri(),
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->json('errorMessage'),
        );
    }
}