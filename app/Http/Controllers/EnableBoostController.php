<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessBoostCollection;
use App\Jobs\ProcessBoostOpenCollection;

class EnableBoostController extends Controller
{
    public function __invoke(Request $request)
    {
        $api_key = $request->input('api_key');

        ProcessBoostCollection::dispatch($api_key);
        ProcessBoostOpenCollection::dispatch($api_key);

        return 'Done';
    }
}
