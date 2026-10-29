<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SetuBbpsService;
use Throwable;

class BbpsController extends Controller
{
    public function index()
    {
        return view('bbps.index');
    }

    public function fetchBill(Request $request,SetuBbpsService $setu) 
    {
        $validated = $request->validate([
            'biller_id' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'digits:10'],
            'customer_params' => ['required', 'array', 'min:1'],
            'customer_params.*.name' => [
                'required', 'string', 'max:100'
            ],
            'customer_params.*.value' => [
                'required', 'string', 'max:200'
            ],
        ]);

        $payload = [
            'agent' => [
                'id' => config('bbps.agent_id'),
            ],
            'biller' => [
                'id' => $validated['biller_id'],
            ],
            'customer' => [
                'mobile' => $validated['mobile'],
                'customerParams' => $validated['customer_params'],
            ],
        ];

        try {
            $result = $setu->request(
                'bbps/bills/fetch/request',
                $payload
            );

            return response()->json([
                'success' => true,
                'message' => 'Fetch request submitted.',
                'request' => $result,
            ]);
        } catch (Throwable $e) {
         
            return response()->json([
               'success' => false,
               'message' => 'Bill fetch failed. Check the server logs.',
            ], 502);
        }
    }

    public function fetchStatus(Request $request, SetuBbpsService $setu) 
    {
        $validated = $request->validate([
            'ref_id' => ['required', 'string', 'max:200'],
        ]);

        try {
            $result = $setu->request(
                'bbps/bills/fetch/response',
                ['refId' => $validated['ref_id']]
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to check fetch status.',
            ], 502);
        }
    }
}
