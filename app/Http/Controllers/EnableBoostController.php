<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessBoost;

class EnableBoostController extends Controller
{
    public function __invoke(Request $request)
    {
        $api_key = $request->input('api_key');

        ProcessBoost::dispatch($api_key);

        return 'Done';
    }
}
