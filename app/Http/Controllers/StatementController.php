<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Statement;

class StatementController extends Controller
{
    public function page()
    {
        return view('statements.index');
    }

    public function index()
    {
        $statements = Statement::orderByDesc('date')->take(7)->get();

		return new StatementResource($statements);
    }

    public function show()
    {
    }
}
