<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Statement;
use App\Support\Services\TochkaBank\TochkaBankService;

class StatementController extends Controller
{
    public function page()
    {
        return view('statements.index');
    }

    public function index()
    {
        $statements = Statement::orderByDesc('date')->take(2)->get();

        return new StatementResource($statements);
    }

    public function show(Statement $statement, TochkaBankService $service)
    {
        $transactions = $service->api->getStatement(config('services.tochka.account_id'), $statement->external_id)->json();

        return response()->json($transactions);
    }
}
